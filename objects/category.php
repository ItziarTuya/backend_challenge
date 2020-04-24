<?php 
	
	class Category {

		// database connection and table email
	    private $conn;
	    private $table_name = "categories";
	  
	    // object properties
	    public $id;
	    public $name;	    
	    public $description;	    
	    public $created;
	    public $modified;
	  
	    // constructor with $db as database connection
	    public function __construct( $db, $category_name ){
	        
	        $this->conn 	= $db;
	        $this->name 	= $category_name;
		    $this->created 	= date('Y-m-d H:i:s');

	    }

		/**
		 *  READ one category
		 */
    	function readOne(){
      
	        // query to read single record
	        $query = " SELECT * FROM {$this->table_name} WHERE name = :name " ;
	      
	        // prepare query statement
	        $stmt = $this->conn->prepare( $query );
	      
	        // bind name  
	        $stmt->bindParam(":name", $this->name);
	      
	        // execute query
	        $stmt->execute();

	        return $stmt;

        }



	}


?>