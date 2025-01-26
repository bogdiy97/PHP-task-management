<?php 

function get_all_users($conn){
	$sql = "SELECT * FROM users WHERE role =? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute(["employee"]);

	if($stmt->rowCount() > 0){
		$users = $stmt->fetchAll();
	}else $users = 0;

	return $users;
}


function insert_user($conn, $data){
	$sql = "INSERT INTO users (full_name, username, password, role) VALUES(?,?,?, ?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function update_user($conn, $data){
	$sql = "UPDATE users SET full_name=?, username=?, password=?, role=? WHERE id=? AND role=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function delete_user($conn, $data){
	try {
		// Start transactioon
		$conn->beginTransaction();
		
		// First update tasks to remove association
		$sql = "UPDATE tasks SET assigned_to = NULL WHERE assigned_to = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$data[0]]); // $data[0] is the user ID
		
		// Then delete notifications
		$sql = "DELETE FROM notifications WHERE recipient = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$data[0]]);
		
		// Finally delete the user
		$sql = "DELETE FROM users WHERE id = ? AND role = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute($data);
		
		// Commit transaction
		$conn->commit();
		
	} catch(PDOException $e) {
		// Rollback on error
		$conn->rollBack();
		throw $e;
	}
}


function get_user_by_id($conn, $id){
	$sql = "SELECT * FROM users WHERE id =? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$user = $stmt->fetch();
	}else $user = 0;

	return $user;
}

function update_profile($conn, $data){
	$sql = "UPDATE users SET full_name=?,  password=? WHERE id=? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function count_users($conn){
	$sql = "SELECT id FROM users WHERE role='employee'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}