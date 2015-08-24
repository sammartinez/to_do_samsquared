<?php
class Task
{
    private $description;
    private $id;
    private $due_date;
    private $status;


    function __construct($description, $id = null, $due_date = null, $status)
    {
        $this->description = $description;
        $this->id = $id;
        $this->due_date = $due_date;
        $this->status = $status;
    }

    function setDescription($new_description)
    {
        $this->description = (string) $new_description;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getId()
    {
        return $this->id;
    }

    function setDueDate($new_due_date)
    {
        $this->description = (string) $new_due_date;
    }

    function getDueDate()
    {
        return $this->due_date;
    }

    function getStatus()
    {
        return $this->status;
    }

    function setStatus($new_status)
    {
        $this->status = (boolean) $new_status;
    }

    function save()
    {
        $statement = $GLOBALS['DB']->exec("INSERT INTO tasks (description, due_date, status)
            VALUES ('{$this->getDescription()}', '{$this->getDueDate()}', {$this->getStatus()});");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function getAll()
    {
        $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks ORDER BY due_date ASC;");
        $tasks = array();
        foreach($returned_tasks as $task){
            $description = $task['description'];
            $id = $task['id'];
            $due_date = $task['due_date'];
            $status = $task['status'];
            $new_task = new Task($description, $id, $due_date, $status);
            array_push($tasks, $new_task);
        }
        return $tasks;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM tasks;");
    }

    static function find($search_id)
    {
        $found_task = null;
        $tasks = Task::getAll();
        foreach($tasks as $task) {
            $task_id = $task->getId();
            if ($task_id == $search_id) {
                $found_task = $task;
            }
        }
        return $found_task;
    }

    function update($new_description)
    {
        $GLOBALS['DB']->exec("UPDATE tasks SET description = '{$new_description}' WHERE id = {$this->getId()};");
        $this->setDescription($new_description);
    }

    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM tasks WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM categories_tasks WHERE task_id = {$this->getId()};");
    }

    function addCategory($category)
    {
        $GLOBALS['DB']->exec("INSERT INTO categories_tasks (category_id, task_id) VALUES ({$category->getId()}, {$this->getId()});");
    }

    function getCategories()
    {
        $query = $GLOBALS['DB']->query("SELECT category_id FROM categories_tasks WHERE task_id = {$this->getId()};");
        $category_ids = $query->fetchAll(PDO::FETCH_ASSOC);

        $categories = array();
        foreach($category_ids as $id) {
            $category_id = $id['category_id'];
            $result = $GLOBALS['DB']->query("SELECT * FROM categories WHERE id = {$category_id};");

            $returned_category = $result->fetchAll(PDO::FETCH_ASSOC);

            $name = $returned_category[0]['name'];
            $id = $returned_category[0]['id'];
            $new_category = new Category($name, $id);
            array_push($categories, $new_category);
        }
        return $categories;
    }
}

?>
