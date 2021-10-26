<?php

namespace model;

class Profession extends DbModel implements ModelInterface
{
    protected $id;
    protected $name;

    private const TABLE = 'profession';

    public function __clone() {
        $this->id(null);
    }

    /**
     * @param int $value
     * @return Profession
     */
    public function id(int $value): Profession {
        $this->id = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return Profession
     */
    public function name(string $value): Profession {
        $this->name = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getAll(): array {
        $sql = "SELECT * FROM " . self::TABLE;
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
     * @return Profession|null
     * @throws \Exception
     */
    public function get(): ?Profession {
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
     * @return Profession|null
     * @throws \Exception
     */
    public function getByName(): ?Profession {
        if ($this->name === null) {
            throw new \Exception('Empty value');
        }

        $sql = "SELECT * FROM " . self::TABLE . " WHERE name = ?";
        $stmt = DbConnection::getInstance()->connection()->prepare($sql);
        $stmt->bind_param("s", $this->name);
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
        $existingCompany = $this->getByName();
        if ($existingCompany) {
            return $existingCompany->id;
        }

        $sql = "INSERT INTO " . self::TABLE . " (name) VALUES (?)";
        $stmt = DbConnection::getInstance()->connection()->prepare($sql);
        $stmt->bind_param("s", $this->name);
        $stmt->execute();

        return $stmt->insert_id;
    }
}