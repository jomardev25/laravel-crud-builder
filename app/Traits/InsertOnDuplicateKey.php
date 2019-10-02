<?php

namespace App\Traits;

trait InsertOnDuplicateKey
{
    /**
     * Insert using mysql ON DUPLICATE KEY UPDATE.
     * @link http://dev.mysql.com/doc/refman/5.7/en/insert-on-duplicate.html
     *
     * Example:  $data = [
     *     ['id' => 1, 'name' => 'John'],
     *     ['id' => 2, 'name' => 'Mike'],
     * ];
     *
     * @param array $data is an array of array.
     * @param array $updateColumns NULL or [] means update all columns
     *
     * @return int 0 if row is not changed, 1 if row is inserted, 2 if row is updated
     */
    public function insertOnDuplicateKey(array $data, array $updateColumns = null)
    {
        if (empty($data)) {
            return false;
        }

        // Case where $data is not an array of arrays.
        if (!isset($data[0])) {
            $data = [$data];
        }

        $sql = $this->buildInsertOnDuplicateSql($data, $updateColumns);

        $data = $this->inLineArray($data);

        return $this->getConnection()->affectingStatement($sql, $data);
    }

    /**
     * Insert using mysql INSERT IGNORE INTO.
     *
     * @param array $data
     *
     * @return int 0 if row is ignored, 1 if row is inserted
     */
    public function insertIgnore(array $data)
    {
        if (empty($data)) {
            return false;
        }

        // Case where $data is not an array of arrays.
        if (!isset($data[0])) {
            $data = [$data];
        }

        $sql = $this->buildInsertIgnoreSql($data);
        $data = $this->inLineArray($data);

        return $this->getConnection()->affectingStatement($sql, $data);
    }

    /**
     * Insert using mysql REPLACE INTO.
     *
     * @param array $data
     *
     * @return int 1 if row is inserted without replacements, greater than 1 if rows were replaced
     */
    public function replace(array $data)
    {
        if (empty($data)) {
            return false;
        }

        // Case where $data is not an array of arrays.
        if (!isset($data[0])) {
            $data = [$data];
        }

        $sql = $this->buildReplaceSql($data);

        $data = $this->inLineArray($data);

        return $this->getConnection()->affectingStatement($sql, $data);
    }

    /**
     * Build the question mark placeholder.  Helper function for insertOnDuplicateKeyUpdate().
     * Helper function for insertOnDuplicateKeyUpdate().
     *
     * @param $data
     *
     * @return string
     */
    protected function buildQuestionMarks($data)
    {
        $row = $this->getFirstRow($data);
        $questionMarks = array_fill(0, count($row), '?');

        $line = '(' . implode(',', $questionMarks) . ')';
        $lines = array_fill(0, count($data), $line);

        return implode(', ', $lines);
    }

    /**
     * Get the first row of the $data array.
     *
     * @param array $data
     *
     * @return mixed
     */
    protected function getFirstRow(array $data)
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Empty data.');
        }

        list($first) = $data;

        if (!is_array($first)) {
            throw new \InvalidArgumentException('$data is not an array of array.');
        }

        return $first;
    }

    /**
     * Build a value list.
     *
     * @param array $first
     *
     * @return string
     */
    protected function getColumnList(array $first)
    {
        if (empty($first)) {
            throw new \InvalidArgumentException('Empty array.');
        }

        return '`' . implode('`,`', array_keys($first)) . '`';
    }

    /**
     * Build a value list.
     *
     * @param array $updatedColumns
     *
     * @return string
     */
    protected function buildValuesList(array $updatedColumns)
    {
        $out = [];

        foreach ($updatedColumns as $key => $value) {
            if (is_numeric($key)) {
                $out[] = sprintf('`%s` = VALUES(`%s`)', $value, $value);
            } else {
                $out[] = sprintf('%s = %s', $key, $value);
            }
        }

        return implode(', ', $out);
    }

    /**
     * Inline a multiple dimensions array.
     *
     * @param $data
     *
     * @return array
     */
    protected function inLineArray(array $data)
    {
        return call_user_func_array('array_merge', array_map('array_values', $data));
    }

    /**
     * Build the INSERT ON DUPLICATE KEY sql statement.
     *
     * @param array $data
     * @param array $updateColumns
     *
     * @return string
     */
    protected function buildInsertOnDuplicateSql(array $data, array $updateColumns = null)
    {
        $first = $this->getFirstRow($data);

        $sql  = 'INSERT INTO `' . $this->getTablePrefix() . $this->getTableName() . '`(' . $this->getColumnList($first) . ') VALUES' . PHP_EOL;
        $sql .=  $this->buildQuestionMarks($data) . PHP_EOL;
        $sql .= 'ON DUPLICATE KEY UPDATE ';

        if (empty($updateColumns)) {
            $sql .= $this->buildValuesList(array_keys($first));
        } else {
            $sql .= $this->buildValuesList($updateColumns);
        }

        return $sql;
    }

    /**
     * Build the INSERT IGNORE sql statement.
     *
     * @param array $data
     *
     * @return string
     */
    protected function buildInsertIgnoreSql(array $data)
    {
        $first = $this->getFirstRow($data);
        $sql  = 'INSERT IGNORE INTO `' . $this->getTablePrefix() . $this->getTableName() . '`(' . $this->getColumnList($first) . ') VALUES' . PHP_EOL;
        $sql .=  $this->buildQuestionMarks($data);

        return $sql;
    }

    /**
     * Build REPLACE sql statement.
     *
     * @param array $data
     *
     * @return string
     */
    protected function buildReplaceSql(array $data)
    {
        $first = $this->getFirstRow($data);

        $sql  = 'REPLACE INTO `' . $this->getTablePrefix() . $this->getTableName() . '`(' . $this->getColumnList($first) . ') VALUES' . PHP_EOL;
        $sql .=  $this->buildQuestionMarks($data);

        return $sql;
    }
}