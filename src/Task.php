<?php
class Task
{
    private $description;
    private $id;

    function __construct($description, $id = null)
    {
        $this->description = $description;
        $this->id = $id;
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


    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO tasks (description) VALUES ('{$this->getDescription()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    static function find($search_name)
    {
        $found_tasks = array();
        $tasks = Task::getAll();
        foreach($tasks as $task){
            //$task_name = $task->getDescription();
            if ($task->getDescription() == $search_name){
                array_push($found_tasks, $task->getDescription());
            }
        }
        return $found_tasks;
    }

    static function getAll()
    {
        $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");
        $tasks = array();
        foreach($returned_tasks as $task){
            $description = $task['description'];
            $id = $task['id'];
            //var_dump($id);
            $new_task = new Task($description, $id);
            array_push($tasks, $new_task);
        }
        return $tasks;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM tasks;");
    }
}

?>
