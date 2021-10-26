<?php

namespace model;

class Job extends DbModel implements ModelInterface
{
    protected $id;
    protected $name;
    protected $description;
    protected $expiration;
    protected $openings;
    protected $company_id;
    protected $profession_id;
    protected $visited;

    private const TABLE = 'job';

    public function __clone() {
        $this->id(null);
    }

    /**
     * @param int $value
     * @return Job
     */
    public function id(int $value): Job {
        $this->id = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return Job
     */
    public function name(string $value): Job {
        $this->name = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return Job
     */
    public function description(string $value): Job {
        $this->description = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return Job
     */
    public function expiration(string $value): Job {
        $this->expiration = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return Job
     */
    public function openings(string $value): Job {
        $this->openings = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return Job
     */
    public function company_id(string $value): Job {
        $this->company_id = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return Job
     */
    public function profession_id(string $value): Job {
        $this->profession_id = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return Job
     */
    public function visited(string $value): Job {
        $this->visited = $value;
        return $this;
    }

    /**
     * @param string|null $sortBy
     * @param null $companyId
     * @param null $professionId
     * @return array
     * @throws \Exception
     */
    public function getAll(string $sortBy = null, $companyId = null, $professionId = null): array {
        $sql = "SELECT * FROM " . self::TABLE;

        $addSql = " WHERE ";
        if ($companyId !== null) {
            $company = (new Company())->id((int)$companyId)->get();
            if ($company) {
                $sql .= $addSql . "company_id = " . $company->id;
                $addSql = " AND ";
            }
        }
        if ($professionId !== null) {
            $profession = (new Profession())->id((int)$professionId)->get();
            if ($profession) {
                $sql .= $addSql . "profession_id = " . $profession->id;
                $addSql = " AND ";
            }
        }

        if ($sortBy !== null) {
            $sql .= " ORDER BY ";
            switch ($sortBy) {
                case "nameDesc":
                    $sql .= "name DESC";
                    break;
                case "openingsAsc":
                    $sql .= "openings ASC";
                    break;
                case "openingsDesc":
                    $sql .= "openings DESC";
                    break;
                default:
                case "nameAsc":
                    $sql .= "name ASC";
                    break;
            }
        }

        $stmt = DbConnection::getInstance()->connection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $return = [];
        while ($assoc = $result->fetch_assoc()) {
            $return[] = $this->fromArray($assoc);
        }

        return $return;
    }

    /**
     * @param string $name
     * @return Job|null
     * @throws \Exception
     */
    public function get(): ?Job {
        if ($this->id === null) {
            throw new \Exception('Empty value');
        }

        $sql = "SELECT * FROM " . self::TABLE . " WHERE id = ?";
        $stmt = DbConnection::getInstance()->connection()->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $resultArray = $result->fetch_assoc();
        if (!$resultArray) {
            return null;
        }

        return $this->fromArray($resultArray);
    }

    /**
     * @param string $name
     * @return Job|null
     * @throws \Exception
     */
    public function getByName(): ?Job {
        if ($this->name === null || $this->company_id === null || $this->profession_id === null) {
            throw new \Exception('Empty value');
        }

        $sql = "SELECT * FROM " . self::TABLE . " WHERE name = ? AND company_id = ? AND profession_id = ?";
        $stmt = DbConnection::getInstance()->connection()->prepare($sql);
        $stmt->bind_param("sii", $this->name, $this->company_id, $this->profession_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $resultArray = $result->fetch_assoc();
        if (!$resultArray) {
            return null;
        }

        return $this->fromArray($resultArray);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function add(): int {
        $existingJob = $this->getByName();
        if ($existingJob) {
            return $existingJob->id;
        }

        $sql = "INSERT INTO " . self::TABLE . " (name, description, expiration, openings, company_id, profession_id, visited) VALUES (?, ?, ?, ?, ?, ?, 1)";
        $stmt = DbConnection::getInstance()->connection()->prepare($sql);
        $stmt->bind_param("sssiii", $this->name, $this->description, $this->expiration, $this->openings, $this->company_id, $this->profession_id);
        $stmt->execute();

        return $stmt->insert_id;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function save(): int {
        $existingJob = $this->getByName();
        if ($existingJob) {
            $sql = "UPDATE " . self::TABLE . " SET description = ?, expiration = ?, openings = ?, visited = 1";
            $stmt = DbConnection::getInstance()->connection()->prepare($sql);
            $stmt->bind_param("ssi", $this->description, $this->expiration, $this->openings);
            $stmt->execute();

            return $existingJob->id;
        }

        $sql = "INSERT INTO " . self::TABLE . " (name, description, expiration, openings, company_id, profession_id, visited) VALUES (?, ?, ?, ?, ?, ?, 1)";
        $stmt = DbConnection::getInstance()->connection()->prepare($sql);
        $stmt->bind_param("sssiii", $this->name, $this->description, $this->expiration, $this->openings, $this->company_id, $this->profession_id);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function cleanup() {
        $sql = "DELETE FROM " . self::TABLE . " WHERE visited = 0";
        $stmt = DbConnection::getInstance()->connection()->prepare($sql);
        $stmt->execute();

        $sql = "UPDATE " . self::TABLE . " SET visited = 0";
        $stmt = DbConnection::getInstance()->connection()->prepare($sql);
        $stmt->execute();
    }
}