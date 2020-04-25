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
	        if ($stmt->execute()) {

	        	return $stmt->fetch();
	        }

	        return false;

        }

        function getCategoryId(){

        	if ( !empty( $this->name ) ){

				$res = $this->readOne();

				if ( $res && $res['name'] == $this->name){

					return $res['id'];

				} else{
					// If the category is not empty or isn't on db, create new category.
					if ( $this->create() ) {

						$res = $this->readOne();
						$id  = $res['id'];
					
						return 	$id;
					}

				}

			} else {

				return 8; // Other works.

			}

        }


        public function create(){

        	// query to insert record
	        $query = " INSERT INTO {$this->table_name} SET name=:name, created=:created";
	        
	        // prepare query
	        $stmt = $this->conn->prepare( $query );

	        // bind values
	        $stmt->bindParam( ":name", 		$this->name );
	        $stmt->bindParam( ":created", 	$this->created );
	      
	        // execute query
	        if($stmt->execute()){
	            return true;
	        }
	      
	        return false;
          
    	}

	}
?>