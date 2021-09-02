<?php

    // set_include_path('config');
    require_once "config/db.php";

    class  Database {

        public $selectedResultCount, $selectedResult, $selectedRecords, $selectedRecordsCount;

        public function executeQuery ($sql, $data) {
            global $con;

            $stmt = $con->prepare($sql);
            $values = array_values($data);
            $types = str_repeat('s', count($values));
            $stmt->bind_param($types, ...$values);
            $stmt->execute();

            return $stmt;
        }

        public function updatedb ($tableName, $colombName, $newValue, $rowName, $rowValue) { 
            global $con;

            $sql = "UPDATE $tableName SET $colombName='$newValue' WHERE $rowName='$rowValue'";
            if (mysqli_query($con, $sql)) {
                return true;
            } else {
                return false;
            } 
        }

        public function updateMultiple ($tableName, $datas, $condition1, $Value1, $condition2="", $Value2="", $logic="AND", $operator = "=") { 
            global $con;

            $sql = "UPDATE $tableName SET ";

            $i = 0;
            foreach ($datas as $data => $value) {
                if ($i === 0) {
                    $sql = $sql . " $data=?";
                } else {
                    $sql = $sql . ", $data=?";
                }
                $i++;
            }

            $sql = $sql . " WHERE $condition1='$Value1'";
            if (!empty($condition2)) {
                $sql = $sql . " $logic $condition2 $operator '$Value2'";
            }

            if ($this->executeQuery($sql, $datas)) {
                return true;
            } else {
                return false;
            }
    
        }

        public function updateMultipleConditions ($tableName, $colombName, $newValue, $conditions, $logic="AND", $operator="=") {        
            global $con;

            $sql = "UPDATE $tableName SET $colombName='$newValue' WHERE ";

            $i = 0;
            foreach ($conditions as $condition => $value) {
                if ($i === 0) {
                    $sql = $sql . " $condition=?";
                } else {
                    $sql = $sql . " $logic $condition $operator ?";
                }
                $i++;
            }        

            if ($this->executeQuery($sql, $conditions)) {
                return true;
            } else {
                return false;
            }
    
        }

        public function delete ($tableName, $conditions, $logic = "AND") { 
            global $con;

            $sql = "DELETE FROM $tableName ";

            $i = 0;
            foreach ($conditions as $condition => $value) {
                if ($i === 0) {
                    $sql = $sql . " WHERE $condition=?";
                } else {
                    $sql = $sql . " $logic $condition=?";
                }
                $i++;
            }

            if ($this->executeQuery($sql, $conditions)) {
                return true;
            } else {
                return false;
            }
    
        }

        public function deleteByOrder ($tableName, $conditions, $recordID, $order, $logic = "AND", $operator = "=") { 
            global $con;

            $sql = "DELETE FROM $tableName ";

            $i = 0;
            foreach ($conditions as $condition => $value) {
                if ($i === 0) {
                    $sql = $sql . " WHERE $condition=?";
                } else {
                    $sql = $sql . " $logic $condition $operator?";
                }
                $i++;
            }

            $sql = $sql . " ORDER BY $recordID $order";

            if ($this->executeQuery($sql, $conditions)) {
                return true;
            } else {
                return false;
            }
    
        }
        
        public function insertdb ($tableName, $datas) {
            global $con;

            $sql = "INSERT INTO $tableName SET ";

            $i = 0;
            foreach ($datas as $data => $value) {
                if ($i === 0) {
                    $sql = $sql . " $data=?";
                } else {
                    $sql = $sql . ", $data=?";
                }
                $i++;
            }

            if ($this->executeQuery($sql, $datas)) {
                return true;
            } else {
                return false;
            }
        }

        // this function selects only one (1) record from the database
        public function selectdb ($tableName, $conditions, $logic = "OR") {
            global $con;

            $sql = "SELECT * FROM $tableName ";

            $i = 0;
            foreach ($conditions as $condition => $value) {
                if ($i === 0) {
                    $sql = $sql . " WHERE $condition=?";
                } else {
                    $sql = $sql . " $logic $condition=?";
                }
                $i++;
            }
            $sql = $sql . " LIMIT 1";
            
            $stmt = $this->executeQuery($sql, $conditions);
            $result = $stmt->get_result();

            $this->selectedResult = $result->fetch_assoc();
            $this->selectedResultCount = $result->num_rows;
            $stmt->close();
        }

        // this select all the records available in the database
        public function selectAll ($tableName, $conditions = [], $logic = "OR", $operator = "=") {
            global $con;

            $sql = "SELECT * FROM $tableName ";

            if (empty($conditions)) {
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $records = $stmt->get_result();
            } else {
                $i = 0;
                foreach ($conditions as $condition => $value) {
                    if ($i === 0) {
                        $sql = $sql . " WHERE $condition=?";
                    } else {
                        $sql = $sql . " $logic $condition $operator?";
                    }
                    $i++;
                }

                $stmt = $this->executeQuery($sql, $conditions);
                $records = $stmt->get_result();
            }
            
            $this->selectedRecords = $records->fetch_all(MYSQLI_ASSOC);
            $this->selectedRecordsCount = $records->num_rows;
            $stmt->close();
        }

        // this function selects all the data in the database and specifies 
        // the number or record to display at a time 
        public function selectAlldb ($tableName, $recordID, $offset, $no_of_records_per_page, $order = "DESC", $conditions = [], $logic = "OR") {
            global $con;
        
            $sql = "SELECT * FROM $tableName ORDER BY $recordID $order LIMIT $offset, $no_of_records_per_page";
        
            if (empty($conditions)) {
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $records = $stmt->get_result();
                // return $records;
            } else {
                $sql = "SELECT * FROM $tableName ";
                $i = 0;
                foreach ($conditions as $condition => $value) {
                    if ($i === 0) {
                        $sql = $sql . " WHERE $condition=?";
                    } else {
                        $sql = $sql . " $logic $condition=?";
                    }
                    $i++;
                }
                $sql = $sql . " ORDER BY $recordID $order LIMIT $offset, $no_of_records_per_page";
        
                $stmt = $this->executeQuery($sql, $conditions);
                $records = $stmt->get_result();            
            }
            
            $this->selectedRecords = $records->fetch_all(MYSQLI_ASSOC);
            $this->selectedRecordsCount = $records->num_rows;
            $stmt->close();
        }

        public function selectFirstOrLastRecord ($tableName, $recordID, $order = "ASC", $conditions = [], $logic = "OR", $operator = "=") {
            global $con;

            $sql = "SELECT * FROM $tableName ";
            
            if (empty($conditions)) {
                $sql = $sql . " ORDER BY $recordID $order LIMIT 1";

                $stmt = $con->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                // return $records;
            } else {
                $i = 0;
                foreach ($conditions as $condition => $value) {
                    if ($i === 0) {
                        $sql = $sql . " WHERE $condition=?";
                    } else {
                        $sql = $sql . " $logic $condition $operator?";
                    }
                    $i++;
                }
                $sql = $sql . " ORDER BY $recordID $order LIMIT 1";
        
                $stmt = $this->executeQuery($sql, $conditions);
                $result = $stmt->get_result();            
            }
            
            $this->selectedResult = $result->fetch_assoc();
            $this->selectedResultCount = $result->num_rows;
            $stmt->close();

        }    
        
    }

    class DistanceMatrix {

        public $Distance, $Duration, $details;

        // bingMaps Developer's API
        public function bingMaps($A, $B){
            // bingMaps Developer's API
            $Key = "AlJZPGnCGfHNTwSc6uJpyQ3-0zhhSLJYwxOxOg5NWlgdc4CcYCzqjmDYh0ruvClz";

            // https://dev.virtualearth.net/REST/v1/Routes/DistanceMatrix?origins={lat0,long0;lat1,lon1;latM,lonM}&destinations={lat0,lon0;lat1,lon1;latN,longN}&travelMode={travelMode}&startTime={startTime}&timeUnit={timeUnit}&key={BingMapsAPIKey}

            $URL = "https://dev.virtualearth.net/REST/v1/Routes/DistanceMatrix?origins=".urlencode($A)."&destinations=".urlencode($B)."&travelMode=driving&key=".urlencode($Key);
            $Data = json_decode(file_get_contents($URL));
        
            $statusCode = str_replace(" mi", "", $Data->statusCode);
            $statusDescription = str_replace(" mi", "", $Data->statusDescription);
            // $this->Distance = $Data;
            // $this->Duration = $Data;

            if ($statusCode == 200 || $statusDescription == "OK") {
                $this->Distance = str_replace(" mi", "", $Data->resourceSets[0]->resources[0]->results[0]->travelDistance);
                $this->Duration = str_replace(" mi", "", $Data->resourceSets[0]->resources[0]->results[0]->travelDuration);
            } else {
                $this->Distance =  $statusCode;
                $this->Duration =  $statusDescription;
            }
                            
        }
        
        // google maps distance matrix Developer's API
        public function google($A, $B){
            $apiKey1 = "AIzaSyCmQq3O6FlZpzI5VjhpD_8gcRNpgsRPj5Y";
            $apiKey2 = "AIzaSyDrbruQzJtGK0zBpL9WHBRGnp0EAcms-xM";

            $URL = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".urlencode($A)."&destinations=".urlencode($B)."&key=".urlencode($apiKey2);
            $Data = json_decode(file_get_contents($URL));
        
            $status1 = str_replace(" mi", "", $Data->status);
            $status2 = str_replace(" mi", "", $Data->rows->elements->status);

            if ($status1 == "OK") {
                if ($status2 == "OK") {
                    $this->Distance = str_replace(" mi", "", $Data->rows->elements->distance->text);
                    $this->Duration = str_replace(" mi", "", $Data->rows->elements->duration->text);
                }else {
                    $this->Distance =  str_replace(" mi", "", $Data->rows->elements->status);
                    $this->Duration =  str_replace(" mi", "", $Data->rows->elements->status);
                }            
            } else {
                $this->Distance =  str_replace(" mi", "", $Data->status);
                $this->Duration =  str_replace(" mi", "", $Data->status);
            }
                            
        }

        // MapQuest Developer's API
        public function mapQuest($A, $B){
            // MapQuest Developer's API
            $Key = "ogBF0ilTv259Q5G4pZJKMazs5pSpCIXM";
            $Secret = "C0dPaJqIZ1BS1lp0";

            $URL = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".urlencode($A)."&destinations=".urlencode($B)."&key=".urlencode($Key);
            $Data = json_decode(file_get_contents($URL));
        
            $status1 = str_replace(" mi", "", $Data->status);
            $status2 = str_replace(" mi", "", $Data->rows->elements->status);

            if ($status1 == "OK") {
                if ($status2 == "OK") {
                    $this->Distance = str_replace(" mi", "", $Data->rows->elements->distance->text);
                    $this->Duration = str_replace(" mi", "", $Data->rows->elements->duration->text);
                }else {
                    $this->Distance =  str_replace(" mi", "", $Data->rows->elements->status);
                    $this->Duration =  str_replace(" mi", "", $Data->rows->elements->status);
                }            
            } else {
                $this->Distance =  str_replace(" mi", "", $Data->status);
                $this->Duration =  str_replace(" mi", "", $Data->status);
            }
                            
        }

        public function Distance($A, $B){
            $apiKey1 = "AIzaSyCmQq3O6FlZpzI5VjhpD_8gcRNpgsRPj5Y";
            $apiKey2 = "AIzaSyDrbruQzJtGK0zBpL9WHBRGnp0EAcms-xM";

            $URL = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".urlencode($A)."&destinations=".urlencode($B)."&key=".urlencode($apiKey1);
            $Data = json_decode(file_get_contents($URL));
        
            $status1 = str_replace(" mi", "", $Data->status);
            $status2 = str_replace(" mi", "", $Data->rows->elements->status);

            if ($status1 == "OK") {
                if ($status2 == "OK") {
                    return str_replace(" mi", "", $Data->rows->elements->distance->text);
                }else {
                    return str_replace(" mi", "", $Data->rows->elements->status);
                }            
            } else {
                return str_replace(" mi", "", $Data->status);
            }

        }

        public function Duration($A, $B){
            $apiKey1 = "AIzaSyCmQq3O6FlZpzI5VjhpD_8gcRNpgsRPj5Y";
            $apiKey2 = "AIzaSyDrbruQzJtGK0zBpL9WHBRGnp0EAcms-xM";

            $URL = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".urlencode($A)."&destinations=".urlencode($B)."&key=".urlencode($apiKey1);
            $Data = json_decode(file_get_contents($URL));
        
            return str_replace(" mi", "", $Data->rows->elements->duration->text);        
        }

        public function ORdistance($A, $B){
            global $apiKey1, $apiKey2;

            $URL = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".urlencode($A)."&destinations=".urlencode($B)."&key=".urlencode($apiKey1);
            $Data = json_decode(file_get_contents($URL));
        
            return str_replace(' mi', '',$Data->rows[0]->elements[0]->distance->text);        
        }

    }

?>