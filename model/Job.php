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

//    public function getAll(): array {
//        $sql = "SELECT * FROM " . self::TABLE;
//        $stmt = DbConnection::getInstance()->connection()->prepare($sql);
//        $stmt->execute();
//        $result = $stmt->get_result();
//        return $this->fromObject($result->fetch_assoc());
//    }

    /**
     * @param string $name
     * @return Job|null
     * @throws \Exception
     */
    public function get(): ?Job {
        if ($this->name === null || $this->company_id === null || $this->profession_id === null) {
            throw new \Exception('Empty value');
        }

        $sql = "SELECT * FROM " . self::TABLE . " WHERE name = ? AND company_id = ? AND profession_id = ?";
        $stmt = DbConnection::getInstance()->connection()->prepare($sql);
        $stmt->bind_param("sii", $this->name, $this->company_id, $this->profession_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            return null;
        }

        return $this->fromObject($result->fetch_assoc());
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function add(): int {
        $existingJob = $this->get();
        if ($existingJob) {
            return $existingJob->id;
        }

        $sql = "INSERT INTO " . self::TABLE . " (name, description, expiration, openings, company_id, profession_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = DbConnection::getInstance()->connection()->prepare($sql);
        $stmt->bind_param("sssiii", $this->name, $this->description, $this->expiration, $this->openings, $this->company_id, $this->profession_id);
        $stmt->execute();

        return $stmt->insert_id;
    }
}