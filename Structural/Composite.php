<?php

interface Todo
{
    public function getHtml(): string;
}

/*
 * Leaf class - will be leaf node in tree structure
 */
class TodoListItem implements Todo
{
    protected string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getHtml(): string
    {
        return "<input type='checkbox' name='{$this->text}'>" . ' ' . $this->text;
    }
}

/*
 * Composite class - will be branch node in tree structure
 */
class TodoList implements Todo
{
    protected string $title;
    protected array $todoLists;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function addToList(Todo $todo): void
    {
        $this->todoLists[] = $todo;
    }

    public function removeFromList(Todo $todo): void
    {
        $this->todoLists = array_filter($this->todoLists, function ($listItem) use ($todo) {
            return $listItem != $todo;
        });
    }

    public function getHtml(): string
    {
        $html = "<h3>{$this->title}</h3><ul style='list-style-type: none'>";

        foreach($this->todoLists as $todoList) {
            $html .= "<li>{$todoList->getHtml()}</li>";
        }

        $html .= "</ul><br />";

        return $html;
    }
}

/*
 * Client
 */
function getTodoList(string $title, array $taskList): Todo
{
    $todo = new TodoList($title);

    foreach($taskList as $index => $listItem) {

        if(gettype($listItem) === 'string') {
            $todo->addToList(new TodoListItem($listItem));
        } elseif(gettype($listItem) === 'array') {

            $todo->addToList($todoList = new TodoList($index));

            foreach($listItem as $item) {
                if(gettype($item) === 'string') {
                    $todoList->addToList(new TodoListItem($item));
                } else {
                    throw new Exception('This task type is not valid');
                }
            }
        } else {
            throw new Exception('This task type is not valid');
        }

    }

    return $todo;
}

$taskList = [
        'task1', 'task2',
        'task3' => ['task31', 'task32', 'task33'],
        'task4', 'task5'
    ];

$todo = getTodoList('Todo List', $taskList);

echo $todo->getHtml();

/*
 * Output:
 *
 * Todo List
 *  □ task1
 *  □ task2
 *  task3
 *      □ task31
 *      □ task32
 *      □ task33
 *  □ task4
 *  □ task5
 */
