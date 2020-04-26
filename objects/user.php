<?php 

	class User {

		// database connection and table email
	    private $conn;
	    private $table_name = "users";
	  
	    // object properties
	    public $id;
	    public $email;
	    public $phone;
	    public $address;
	    public $created;
	    public $modified;
	  
	    // constructor with $db as database connection
	    public function __construct( $db, $params = null ){
	        
	        $this->conn 	= $db;
	        $this->email 	= $params['email'];
		    $this->phone  	= $params['phone'];
		    $this->address	= $params['address'];
		    $this->created 	= date('Y-m-d H:i:s');

	    }


		/**
		 *  READ one user
		 */
    	function readOne( $email ){
      
	        // query to read single record
	        $query = " SELECT * FROM {$this->table_name} WHERE email = :email " ;
	      
	        // prepare query statement
	        $stmt = $this->conn->prepare( $query );
	      
	        // bind user email 
	        $stmt->bindParam(":email", $email);
	      
	        // execute query
	        if ( $stmt->execute() ) {

	        	//var_dump($stmt->fetch());
	        	//die();

	        	return $stmt->fetch();

	        }

	        return false;

        }


        /**
		 *  CREATE user
		 */
		function create(){

			// insert new budget
			$query = "INSERT INTO {$this->table_name}
		          SET
	                    email 	= :email,
	                    phone 	= :phone,
	                    address = :address,
	                    created = :created ";

			$stmt = $this->conn->prepare($query);
		  
		    // bind values
		    $stmt->bindParam(":email", $this->email);
		    $stmt->bindParam(":phone", $this->phone);
		    $stmt->bindParam(":address", $this->address);
		    $stmt->bindParam(":created", $this->created);

		  	if($stmt->execute()) return $stmt;

		  	return false;	
    	}


        /**
		 *  UPDATE user
		 */
    	function update( $id ){
      
        // update query
        $query = " UPDATE {$this->table_name}
                 	SET
	                    phone 	= :phone,
	                    address = :address
	                WHERE
	                    id 	= :id ";
      
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
      
        // bind new values
        $stmt->bindParam( ":phone", $this->phone );
        $stmt->bindParam( ":address", $this->address );
        $stmt->bindParam( ":id", $id );

        // execute the query
        if($stmt->execute()) return $stmt;

	  	return false;

	    }

	}
    
?>