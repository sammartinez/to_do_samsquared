<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";

    $app = new Silex\Application();

    $server = 'mysql:host=localhost;dbname=to_do';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));
    //Home page
    $app->get("/", function() use ($app)
    {
        return $app['twig']->render('index.html.twig');
    });

    $app->get("/tasks", function() use ($app)
    {
        return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll()));
    });

    $app->post("/tasks", function() use ($app){
        $task = new Task($_POST['name']);
        if (strlen($task->getDescription()) > 0){
            $task->save();
            return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll()));
        }else{
            return $app['twig']->render('error.html.twig');
        }
    });
    // search for task function
    $app->get("/task_search_results", function() use ($app){
        $task_to_search = $_GET['search_name'];
        if (strlen($task_to_search) > 0){
            //change Task::find() to return array
            //=================================
            $found_tasks = (Task::find($task_to_search));
            var_dump($found_tasks);
            return $app['twig']->render('task_search_results.html.twig', array('tasks' => $found_tasks));
        } else{
            return $app['twig']->render('error.html.twig');
        }
    });



    $app->post("/delete_tasks", function() use ($app){
        Task::deleteAll();
        return $app['twig']->render('delete_tasks.html.twig');
    });

    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('categories.html.twig', array('categories' => Category::getAll()));
    });

    $app->post("/categories", function() use ($app){
        $category = new Category($_POST['name']);
        if (strlen($category->getName()) > 0){
            $category->save();
            return $app['twig']->render('categories.html.twig', array('categories' => Category::getAll()));
        }else{
            return $app['twig']->render('error.html.twig');
        }
    });

    $app->post("/delete_categories", function() use ($app){
        Category::deleteAll();
        return $app['twig']->render('delete_categories.html.twig');
    });



    return $app;
?>
