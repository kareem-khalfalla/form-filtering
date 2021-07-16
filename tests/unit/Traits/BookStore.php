<?php

trait BookStore
{
    use ModelData;

    protected $lastInsertId;
    
    /**
     * Add book record.
     *
     * @param  array $data
     * @return bool
     */
    protected function addBook(array $data = []):bool
    {
        /** @var \App\Database\Database $db */
        $db = $this->db;

        $inserted = $db->insert(
            'books',
            $this->bookData($data)
        );

        var_dump($inserted);

        if ($inserted){
            $this->lastInsertId = $db->lastInsertId();
            return true;
        }

        return false;
    }
}