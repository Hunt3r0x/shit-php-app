<?php
require_once 'vendor/autoload.php';
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

// Define your User type
$userType = new ObjectType([
    'name' => 'User',
    'fields' => [
        'id' => Type::nonNull(Type::int()),
        'username' => Type::string(),
        'email' => Type::string(),
        'posts' => [
            'type' => Type::listOf($postType),
            'resolve' => function ($user) {
                // Fetch posts for this user from the database
            },
        ],
    ],
]);

// Define your Post type
$postType = new ObjectType([
    'name' => 'Post',
    'fields' => [
        'id' => Type::nonNull(Type::int()),
        'title' => Type::string(),
        'content' => Type::string(),
    ],
]);

// Define your queries
$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'user' => [
            'type' => $userType,
            'args' => [
                'id' => Type::nonNull(Type::int()),
            ],
            'resolve' => function ($root, $args) {
                // Fetch user by ID
            },
        ],
        'posts' => [
            'type' => Type::listOf($postType),
            'resolve' => function () {
                // Fetch all posts
            },
        ],
    ],
]);

// Create the schema
$schema = new Schema($queryType);

// Get the query from the request
$input = json_decode(file_get_contents('php://input'), true);
$query = $input['query'];

// Execute the query
$result = GraphQL::executeQuery($schema, $query);
$output = $result->toArray();

// Return the response
header('Content-Type: application/json');
echo json_encode($output);
?>