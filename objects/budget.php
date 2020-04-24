<?php 

include_once 'user.php';
include_once 'category.php';

	class Budget {

		// database connection and table name
	    private $conn;
	    private $table_name = "budgets";
	  
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
	    public function __construct( $db ){
	        $this->conn = $db;
	    }

		/**
		 *  READ budget
		 */
		function read(){
		  
		    // select all query
		    $query = " SELECT p.id, p.title, p.description, p.category_id, c.name as category_name, p.user_id, u.				name as user_name, p.status_id, s.name as status_name, p.created
			            FROM {$this->table_name} p
		                LEFT JOIN categories c 	ON p.category_id = c.id
		                LEFT JOIN users u 		ON p.user_id = u.id
		                LEFT JOIN status s 		ON p.status_id = s.id
			            ORDER BY p.created DESC ";
		  
		    // prepare query statement
		    $stmt = $this->conn->prepare( $query );
		  
		    // execute query
		    $stmt->execute();
		  
		    return $stmt;

		}

		/**
		 *  CREATE budget
		 */
		function create(){

			// instantiate database and user object
			$database 	= new Database();
			$db 		= $database->getConnection();
			$params		= array (
				'email' 	=> filter_var( $this->email, FILTER_SANITIZE_EMAIL ),
		    	'phone'		=> filter_var( $this->phone, FILTER_SANITIZE_STRING ),
		    	'address'	=> filter_var( $this->address, FILTER_SANITIZE_STRING ) 
		    );
			$user 		= new User( $db, $params);
			$stmt 		= $user->readOne();
			$res 		= $stmt->fetch();

			// Transaction init
			$this->conn->beginTransaction();

			// get budget user_id
			if ( !$res ){

				// create new user
				$stmt1 		= $user->create();
				$stmt 		= $user->readOne();
				$res    	= $stmt->fetch();

			} else {
				// update old user
				$stmt1 = $user->update( $res['id'] );

			}

			$user_id = $res['id'];


			// get butget category_id
			if ( !empty( $this->category ) ){
			
				$category_name  = strtolower( filter_var( $this->category, FILTER_SANITIZE_STRING ) );
				$category 		= new Category( $db, $category_name );
				$stmt 			= $category->readOne();
				$res 			= $stmt->fetch();

				if ( $res && $res['name'] == $category_name) {

					$category_id = $res['id'];

				} else {

					$category_id = 8; // Other works.
				}

			}

			$status_id	= 3;
			$created 	= date('Y-m-d H:i:s');

			// insert new budget
			$query2 = "INSERT INTO {$this->table_name} 
			          	SET 
			          		title=:title, 
			          		description=:description, 
			          		category_id=:category_id, 
			          		user_id=:user_id, 
			          		status_id=:status_id, 
			          		created=:created ";

			$stmt2 = $this->conn->prepare($query2);

		    // sanitize
		    $this->title 		= filter_var( $this->title, FILTER_SANITIZE_STRING );
		    $this->description 	= filter_var( $this->description, FILTER_SANITIZE_STRING );
		  
		    // bind values
		    $stmt2->bindParam( ":title", $this->title );
		    $stmt2->bindParam( ":description", $this->description );
		    $stmt2->bindParam( ":category_id", $category_id );
		    $stmt2->bindParam( ":user_id", $user_id );
		    $stmt2->bindParam( ":status_id", $status_id );
		    $stmt2->bindParam( ":created", $created );

		    // execute query
		    $stmt2->execute();

			// If both statements are successful, consolidate the transaction. En caso contrario, revertirla
			if( $stmt1 && $stmt2 ) {
			     $this->conn->commit();
			     echo "Consolidated transaction.<br />";
			     return true;
			} else {
			     $this->conn->rollback();
			     echo "Reverted transaction.<br />";
			     return false;
			}
		
		}


	}

?>
