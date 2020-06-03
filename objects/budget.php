<?php

include_once 'user.php';
include_once 'category.php';

class Budget {

    // database connection and table name
    private $conn;
    private $table = "budgets";
    // object properties
    public $id;
    public $title;
    public $description;
    public $category_id;
    public $category_name;
    public $user_id;
    public $user_name;
    public $status_id;
    public $status_name;
    public $created;

    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     *  CREATE budget
     */
    function create() {

        // instantiate database and user object
        $email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $params = array(
            'email' => $email,
            'phone' => filter_var($this->phone, FILTER_SANITIZE_STRING),
            'address' => filter_var($this->address, FILTER_SANITIZE_STRING)
        );
        $user = new User($this->conn, $params);
        $res = $user->readOne($email);

        // Transaction init
        $this->conn->beginTransaction();

        // get budget user_id
        if (!$res) {

            // create new user
            $stmt1 = $user->create();
            $stmt = $user->readOne($email);
        } else {

            // update old user
            $stmt1 = $user->update($res['id']);
        }

        $user_id = $res['id'];

        // get butget category_id
        if (isset($this->category) && !empty($this->category)) {

            $category_name = strtolower(filter_var($this->category, FILTER_SANITIZE_STRING));
            $category = new Category($this->conn, $category_name);
            $category_id = !empty($category->getCategoryId()) ? $category->getCategoryId() : "";
        }

        // pending status by default
        $status_id = 1;
        $created = date('Y-m-d H:i:s');

        // insert new budget
        $query2 = "INSERT INTO {$this->table} SET 
			          		title=:title, 
			          		description=:description, 
			          		category_id=:category_id, 
			          		user_id=:user_id, 
			          		status_id=:status_id, 
			          		created=:created ";

        $stmt2 = $this->conn->prepare($query2);

        // sanitize
        $this->title = filter_var($this->title, FILTER_SANITIZE_STRING);
        $this->description = filter_var($this->description, FILTER_SANITIZE_STRING);

        // bind values
        $stmt2->bindParam(":title", $this->title);
        $stmt2->bindParam(":description", $this->description);
        $stmt2->bindParam(":category_id", $category_id);
        $stmt2->bindParam(":user_id", $user_id);
        $stmt2->bindParam(":status_id", $status_id);
        $stmt2->bindParam(":created", $created);

        // execute query
        $stmt2->execute();

        // If both statements are successful, consolidate the transaction. Otherwise, reverse it.
        if ($stmt1 && $stmt2) {

            $this->conn->commit();
            echo "Consolidated transaction.<br />";
            return true;
        } else {

            $this->conn->rollback();
            echo "Reverted transaction.<br />";
            return false;
        }
    }

    /**
     * UPDATE budget
     */
    function update() {

        // Check if budget exists and is alowed to be modified .
        $id = filter_var($this->id, FILTER_VALIDATE_INT);
        $budget = $this->readOne($id);

        if ($budget && $budget['status_id'] == 1) {

            // get butget category_id
            $database = new Database();
            $db = $database->getConnection();
            $category_name = strtolower(filter_var($this->category, FILTER_SANITIZE_STRING));
            $category = new Category($db, $category_name);
            $category_id = $category->getCategoryId();

            // update query
            $query = "UPDATE {$this->table} SET
		                    title 		= :title,
		                    description = :description,
		                    category_id = :category_id
		                WHERE
		                    id = :id";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->title = filter_var($this->title, FILTER_SANITIZE_STRING);
            $this->description = filter_var($this->description, FILTER_SANITIZE_STRING);

            // bind new values
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':id', $id);

            // execute the query
            if ($stmt->execute()) {
                return true;
            }

            return false;

            // The budget could not be updated.
        } else {
            return false;
        }
    }

    /**
     *  READ one budget
     */
    function readOne($id) {

        // query to read single record
        $query = " SELECT * FROM {$this->table} WHERE id = :id ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind budget id 
        $stmt->bindParam(":id", $id);

        // execute query

        if ($stmt->execute()) {
            return $stmt->fetch();
        }

        return false;
    }

    /**
     *  Post a pending budget request
     */
    function post() {

        // check if budget exists and is alowed to be modified .
        $id = filter_var($this->id, FILTER_VALIDATE_INT);
        $budget = $this->readOne($id);
        $published = 2;

        if ($budget && $budget['status_id'] == 1 && !empty($budget['title']) && !empty($budget['description'])) {

            $query = " UPDATE {$this->table} SET
								status_id = :status_id
							WHERE id = :id ";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":status_id", $published);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                return true;
            }

            return false;

            // the budget does not meet the requirements to be published.
        } else {
            return false;
        }
    }

    /**
     *  Discard a published or pending budget request
     */
    function discard() {

        // check if budget exists and is alowed to be modified .
        $id = filter_var($this->id, FILTER_VALIDATE_INT);
        $budget = $this->readOne($id);
        $discarded = 3;

        if ($budget && ( $budget['status_id'] != $discarded )) {

            $query = " UPDATE {$this->table} SET
								status_id = :status_id
							WHERE id = :id ";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":status_id", $discarded);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                return true;
            }

            return false;

            // the budget does not meet the requirements to be discarded.
        } else {
            return false;
        }
    }

    /**
     *  READ budget with pagination
     */
    function readPaging($from_record_num, $records_per_page) {

        $res = false;

        // select all query
        $query = "SELECT 
		    			b.id, 
	    				b.title, 
	    				b.description, 
	    				b.category_id, 
	    				c.name as category_name, 
	    				b.user_id, 
	    				u.email as user_email, 
	    				b.status_id, 
	    				s.name as status_name, 
	    				b.created
		            FROM {$this->table} b
	                LEFT JOIN categories c 	ON b.category_id = c.id
	                LEFT JOIN users u 		ON b.user_id = u.id
	                LEFT JOIN status s 		ON b.status_id = s.id ";

        if (isset($this->email)) {

            $database = new Database();
            $db = $database->getConnection();
            $user = new User($db);
            $res = $user->readOne(filter_var($this->email, FILTER_SANITIZE_EMAIL));

            if ($res) {

                $query .= " WHERE b.user_id = :user_id ";

                // The email doesn't exist in db.
            } else {

                return false;
            }
        }

        $query .= " ORDER BY b.created DESC
						LIMIT $from_record_num, $records_per_page ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        if ($res) {

            $stmt->bindParam(":user_id", $res["id"]);
        }

        // execute query
        if ($stmt->execute()) {

            return $stmt;
        }

        return false;
    }

    // Count rows for paging budgets
    public function count($email = null) {


        $query = "SELECT COUNT(*) as total_rows FROM {$this->table}";

        $res = false;
        if (isset($this->email)) {

            $database = new Database();
            $db = $database->getConnection();
            $user = new User($db);
            $res = $user->readOne(filter_var($this->email, FILTER_SANITIZE_EMAIL));

            if ($res)
                $query .= " WHERE user_id = :user_id ";
        }

        $stmt = $this->conn->prepare($query);

        if ($res)
            $stmt->bindParam(":user_id", $res["id"]);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }

}