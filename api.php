<?php

# Errors

error_reporting(E_ALL);
ini_set('display_errors', 1);

# Headers setup

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header('Content-Type: application/json; charset=utf-8');

# Composer & vendor

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require __DIR__ . '/vendor/autoload.php';
}

# SQLite database 'app.db'

try {
    $conn = new PDO('sqlite:app.db');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Exception $e) {
    echo "Database Error: " . $e->getMessage();
}

# Namespaces

use Ramsey\Uuid\Uuid;

# GET | POST | PUT | DELETE

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
    case "GET":
        echo json_encode(getUsers());
        break;

    case "POST":
        echo json_encode(insertUser());
        break;

    case "PUT":
        echo json_encode(updateUser());
        break;

    case "DELETE":
        echo json_encode(deleteUser());
        break;

    default :
        $response = [
            'status' => 418, 
            'statusText' => 'Default setting, I am a teapot',
            'payload' => [],
        ];
        echo json_encode($response);
}

# App codebase

/**
 * Get all users / Get user by id in URL
 */
function getUsers(): array {
    global $conn;
    $sql = "SELECT * FROM users WHERE deleted_at IS NULL";
    $path = explode('/', $_SERVER['REQUEST_URI']);

    if (
        isset($path[2]) 
        && is_numeric($path[2])
    ) {
        # By id
        $sql .= " AND id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $path[2]);
        $stmt->execute();
        $users = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        # All
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return [
        'status' => 200, 
        'statusText' => '',
        'payload' => $users,
    ];
}

/**
 * Insert user
 */
function insertUser(): array {
    global $conn;
    $user = json_decode(file_get_contents('php://input'));

    # The RETURNING syntax has been supported by SQLite since version 3.35.0
    # $sql = "INSERT INTO users(id, name, email, phone, token, created_at) VALUES(null, :name, :email, :phone, :token, :created_at) RETURNING *";
    $sql = "INSERT INTO users(id, name, email, phone, token, created_at) VALUES(null, :name, :email, :phone, :token, :created_at)";

    $token = Uuid::uuid7()->__toString();
    $date = date('Y-m-d');

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $user->name);
    $stmt->bindParam(':email', $user->email);
    $stmt->bindParam(':phone', $user->phone);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':created_at', $date);

    if ($stmt->execute()) {
        $sql = "SELECT * FROM users WHERE id = (SELECT last_insert_rowid()) ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $response = [
            'status' => 201, 
            'statusText' => 'Record created successfully.',
            'payload' => $stmt->fetch(PDO::FETCH_ASSOC),
        ];
    } else {
        $response = [
            'status' => 409, 
            'statusText' => 'Failed to create record.',
            'payload' => [],
        ];
    }

    return $response;
}

/**
 * Update user
 */
function updateUser(): array {
    global $conn;
    $user = json_decode( file_get_contents('php://input') );

    # The RETURNING syntax has been supported by SQLite since version 3.35.0
    # $sql = "UPDATE users SET name= :name, email =:email, phone =:phone, updated_at =:updated_at WHERE id = :id RETURNING *";
    $sql = "UPDATE users SET name= :name, email =:email, phone =:phone, updated_at =:updated_at WHERE id = :id";
    $path = explode('/', $_SERVER['REQUEST_URI']);
    $updated_at = date('Y-m-d');

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $path[2]);
    $stmt->bindParam(':name', $user->name);
    $stmt->bindParam(':email', $user->email);
    $stmt->bindParam(':phone', $user->phone);
    $stmt->bindParam(':updated_at', $updated_at);

    if ($stmt->execute()) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $path[2]);
        $stmt->execute();

        $response = [
            'status' => 200, 
            'statusText' => 'Record updated successfully.',
            'payload' => $stmt->fetch(PDO::FETCH_ASSOC),
        ];
    } else {
        $response = [
            'status' => 400, 
            'statusText' => 'Failed to update record.',
            'payload' => [],
        ];
    }

    return $response;
}

/**
 * Delete user - id in URL
 */
function deleteUser(): array {
    global $conn;

    # Hard delete
    # $sql = "DELETE FROM users WHERE id = :id";

    # Soft delete
    $sql = "UPDATE users SET deleted_at =:deleted_at WHERE id = :id";
    $path = explode('/', $_SERVER['REQUEST_URI']);
    $deleted_at = date('Y-m-d');

    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $path[2]);
    $stmt->bindParam(':deleted_at', $deleted_at);

    if ($stmt->execute()) {
        $response = [
            'status' => 200, 
            'statusText' => 'Record deleted successfully.',
            'payload' => [],
        ];
    } else {
        $response = [
            'status' => 409, 
            'statusText' => 'Failed to delete record.',
            'payload' => [],
        ];
    }

    return $response;
}
