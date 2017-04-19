<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// to get all customer records ...
$app->get('/api/v1/customers' , function (Request $request, Response $response) {
    $sql = "SELECT * from customer";
    try
    {
          // new instance that db object and boot the db connection ..
          $db = new DB() ;
          $db = $db->connect();

          $statement = $db->query($sql);
          $customers = $statement->fetchAll(PDO::FETCH_OBJ);
          $db = null ;

          // adding the label for that result and ..returning as json.
          return $response->withHeader(
           'Content-Type',
           'application/json'
           )->withJson(['customers' => $customers]);

    } catch(PDOException $e)
    {
        echo '{"error":{"text": '.$e->getMessage().'}}';
    }

});

// to view a single user ...

$app->get('/api/v1/customer/{id}', function(Request $request , Response $response){

      $customerID = $request->getAttribute('id');

      $activeRecordSql = "SELECT * from customer where customerID = $customerID";

      try{

        $db = new DB() ;
        $db = $db->connect();
        $activeRecordStmt = $db->query($activeRecordSql);
        $activeCustomer = $activeRecordStmt->fetchAll(PDO::FETCH_OBJ);
        $db = null ;

        if( $activeCustomer != []){
          return $response->withHeader(
           'Content-Type',
           'application/json'
           )->withJson(['customerID' => $activeCustomer ]);

        }else{
          return $response->withHeader(
           'Content-Type',
           'application/json'
           )->withJson(['error' => "No Found"])->withStatus(404);
        }

      }catch(PDOException $e){
        echo '{"error":{"text": '.$e->getMessage().'}}';
      }

});


// to add a new customer ....
$app->post('/api/v1/customer/add' , function(Request $request , Response $response){

      // required fields ...
      $firstName = $request->getParam('firstName');
      $lastName = $request->getParam('lastName');
      $gender = $request->getParam('gender');
      $email = $request->getParam('email');
      $phoneNumber = $request->getParam('phoneNumber');
      $addressOne = $request->getParam('addressOne');
      $addressTwo = $request->getParam('addressTwo');
      $city = $request->getParam('city');
      $state = $request->getParam('state');
      $zip = $request->getParam('zip');
      $country = $request->getParam('country');
      $comments = $request->getParam('comments');
      $company = $request->getParam('company');
      $account = $request->getParam('account');
      $total = $request->getParam('total');
      $discount = $request->getParam('discount');
      $taxable = $request->getParam('taxable');

      $insertCustomerSql ="INSERT INTO  customer
                          (   firstName,lastName,gender,email,phoneNumber,addressOne,
                              addressTwo,city,state,zip,country,comments,company,
                              account,total,discount,taxable
                          )
                          VALUES
                          (
                              :firstName,:lastName,:gender,:email,:phoneNumber,:addressOne,
                              :addressTwo,:city,:state,:zip,:country,:comments,:company,
                              :account,:total,:discount,:taxable
                          )" ;

                          try{
                            // new db instance ...
                            $db = new DB();
                            // to connect with db ...
                            $db = $db->connect();
                            // the perpare the statement with query ..
                            $insertCustomerStmt = $db->prepare($insertCustomerSql);

                            // binding the param with the fields
                            $insertCustomerStmt->bindParam(':firstName', $firstName);
                            $insertCustomerStmt->bindParam(':lastName', $lastName);
                            $insertCustomerStmt->bindParam(':gender', $gender);
                            $insertCustomerStmt->bindParam(':email', $email);
                            $insertCustomerStmt->bindParam(':phoneNumber', $phoneNumber);
                            $insertCustomerStmt->bindParam(':addressOne', $addressOne);
                            $insertCustomerStmt->bindParam(':addressTwo', $addressTwo);
                            $insertCustomerStmt->bindParam(':city', $city);
                            $insertCustomerStmt->bindParam(':state', $state);
                            $insertCustomerStmt->bindParam(':zip', $zip);
                            $insertCustomerStmt->bindParam(':country', $country);
                            $insertCustomerStmt->bindParam(':comments', $comments);
                            $insertCustomerStmt->bindParam(':company', $company);
                            $insertCustomerStmt->bindParam(':account', $account);
                            $insertCustomerStmt->bindParam(':total', $total);
                            $insertCustomerStmt->bindParam(':discount', $discount);
                            $insertCustomerStmt->bindParam(':taxable', $taxable);


                            // excute the statement...
                            $insertCustomerStmt->execute();

                            return $response->withHeader(
                             'Content-Type',
                             'application/json'
                             )
                             ->withJson(['message' => "New Customer created successfully ..." ])
                             ->withStatus(200);

                          }catch(PDOException $e){
                              echo '{"error":{"text": '.$e->getMessage().'}}';
                          }

});



// to update the existing records....
$app->put('/api/v1/customer/update/{id}', function(Request $request , Response $response){
        $customerID = $request->getAttribute('id');
        $oldDataSql = "SELECT * FROM customer WHERE customerID = $customerID";

        try{

            $db = new DB();
            $db = $db->connect();

            $oldDataSqlStat = $db->query($oldDataSql);
            $oldData = $oldDataSqlStat->fetchAll(PDO::FETCH_OBJ);
            $db = null ;

            // required fields ...
            $firstName = $request->getParam('firstName') ?? $oldData[0]->firstName;
            $lastName = $request->getParam('lastName') ?? $oldData[0]->lastName;
            $gender = $request->getParam('gender') ?? $oldData[0]->gender;
            $email = $request->getParam('email') ?? $oldData[0]->email;
            $phoneNumber = $request->getParam('phoneNumber') ?? $oldData[0]->phoneNumber;
            $addressOne = $request->getParam('addressOne') ?? $oldData[0]->addressOne;
            $addressTwo = $request->getParam('addressTwo') ?? $oldData[0]->addressTwo;
            $city = $request->getParam('city') ?? $oldData[0]->city;
            $state = $request->getParam('state') ?? $oldData[0]->state;
            $zip = $request->getParam('zip') ?? $oldData[0]->zip;
            $country = $request->getParam('country') ?? $oldData[0]->country;
            $comments = $request->getParam('comments') ?? $oldData[0]->comments;
            $company = $request->getParam('company') ?? $oldData[0]->company;
            $account = $request->getParam('account') ?? $oldData[0]->account ;
            $total = $request->getParam('total') ?? $oldData[0]->total;
            $discount = $request->getParam('discount') ?? $oldData[0]->discount;
            $taxable = $request->getParam('taxable') ?? $oldData[0]->taxable;

            $updateCustomerSql = "UPDATE `customer`
                                      SET `firstName` = :firstName ,
                                          `lastName`  = :lastName ,
                                          `gender`    = :gender ,
                                          `email`     = :email ,
                                          `phoneNumber` = :phoneNumber,
                                          `addressOne`  = :addressOne ,
                                          `addressTwo` = :addressTwo ,
                                          `city` = :city,
                                          `state` = :state,
                                          `zip` = :zip,
                                          `country` = :country,
                                          `comments` = :comments,
                                          `company` = :company,
                                          `account` = :account,
                                          `total` = :total,
                                          `discount` = :discount,
                                          `taxable` = :taxable

                                      WHERE customerID = $customerID
                                  ";

                                try{

                                    // new db instance ...
                                    $db = new DB();
                                    // to connect with db ...
                                    $db = $db->connect();
                                    // the perpare the statement with query ..
                                    $updateCustomerStmt = $db->prepare($updateCustomerSql);

                                    // binding the param with the fields
                                    $updateCustomerStmt->bindParam(':firstName', $firstName);
                                    $updateCustomerStmt->bindParam(':lastName', $lastName);
                                    $updateCustomerStmt->bindParam(':gender', $gender);
                                    $updateCustomerStmt->bindParam(':email', $email);
                                    $updateCustomerStmt->bindParam(':phoneNumber', $phoneNumber);
                                    $updateCustomerStmt->bindParam(':addressOne', $addressOne);
                                    $updateCustomerStmt->bindParam(':addressTwo', $addressTwo);
                                    $updateCustomerStmt->bindParam(':city', $city);
                                    $updateCustomerStmt->bindParam(':state', $state);
                                    $updateCustomerStmt->bindParam(':zip', $zip);
                                    $updateCustomerStmt->bindParam(':country', $country);
                                    $updateCustomerStmt->bindParam(':comments', $comments);
                                    $updateCustomerStmt->bindParam(':company', $company);
                                    $updateCustomerStmt->bindParam(':account', $account);
                                    $updateCustomerStmt->bindParam(':total', $total);
                                    $updateCustomerStmt->bindParam(':discount', $discount);
                                    $updateCustomerStmt->bindParam(':taxable', $taxable);


                                    // excute the statement...
                                    $updateCustomerStmt->execute();

                                    return $response->withHeader(
                                     'Content-Type',
                                     'application/json'
                                     )
                                     ->withJson(['message' => "Customer Update successfully ..." ])
                                     ->withStatus(200);

            }catch(PDOException $e){
                echo '{"error":{"text": '.$e->getMessage().'}}';
            }

        }catch(PDOException $e){
            echo '{"error":{"text": '.$e->getMessage().'}}';
        }
});


// to delete the single customer

$app->delete('/api/v1/customer/delete/{id}' , function(Request $request , Response $response){
        $customerID = $request->getAttribute('id');
        $deleteCustomerSql = "DELETE FROM customer WHERE customerID=$customerID";

        try {
          $db = new DB();
          $db = $db->connect();
          $deleteCustomerStat = $db->prepare($deleteCustomerSql);
          $deleteCustomerStat->execute();

          $db = null;

          return $response->withHeader(
           'Content-Type',
           'application/json'
           )
           ->withJson(['message' => "Customer Deleted successfully ..." ])
           ->withStatus(200);


        } catch(PDOException $e){
            echo '{"error":{"text": '.$e->getMessage().'}}';
        }


});
