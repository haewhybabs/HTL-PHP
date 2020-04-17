<?php



/** 
 * The billing API takes 1.6secs to process and respond to each request
 * 
 * Executing the entire 10000 customers at a time with take about
 * 
 *      10000*1.6 = 16000s => 4.44hrs
 * 
 * Now, to minimize the time it takes for the execution, 
 * 
 * We make use of Asynchronous operation.
 *  
 * Executing something asynchronously means, you can move on to another task without waiting for the first task to get finished
 * 
 * Originally, PHP performs more of Synchronous operation. However, we could use some libraries to perform the asynchronous operation
 * 
 * Libraries like GuzzlePHP, Pthreads, parallel, curl_multi_add_handle e.t.c
 * 
 * 
 *                               #### APPROACH ####
 * 
 * We will be using GUzzlePHP for this project
 * 
 * We will paginate the 10000 customer data to 5 different variables
 * 
 * such that each of the variable takes 2000 customer data
 * 
 * $data1= [The first 2000 customers]
 * $data2= [the next 2000 customers]
 * $data3= [the next 2000 customers]
 * $data4= [the next 2000 customers]
 * $data5= [the next 2000 customers]
 * 
 * 
 * Then we will use the billing API on each of the data asynchronously 
 * 
 * such that 
 * 
 *                        2000 * 1.6s = 3200
 *                        3200/60 = 53 minutes
 * 
 * Therefore 53 minutes will be used for the billing API since each of the data are executed asynchronously
 * 
 * Then we could set the max limit time to = 1hr(3600s)      
*/

set_time_limit(3600);

#The first 2000 customers
$data1=array();

#The next 2000 customers
$data2=array();

#The next 2000 customers
$data3=array();

#The next 2000 customers
$data4=array();

#The next 2000 customers
$data5=array();


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

$client = new Client();

$total =5;
$requests = function ($total) {
    $uri = 'http://endpoint.com';
    for ($i = 0; $i <$total; $i++) {
        foreach($data1 as $d1 ){
                $billingInfo = array(
                        'id'=>$d1->id,
                        'username'=>$d1->username,
                        'mobile_number'=>$d1->mobile_number,
                        'amount_to_bill'=>$d1->amount_to_bill
                );
                yield new Request('GET', $uri,$billingInfo);
        }

        foreach($data2 as $d2 ){
                $billingInfo = array(
                        'id'=>$d2->id,
                        'username'=>$d2->username,
                        'mobile_number'=>$d2->mobile_number,
                        'amount_to_bill'=>$d2->amount_to_bill
                );
                yield new Request('GET', $uri,$billingInfo);
        }

        foreach($data1 as $d3 ){
                $billingInfo = array(
                        'id'=>$d3->id,
                        'username'=>$d3->username,
                        'mobile_number'=>$d3->mobile_number,
                        'amount_to_bill'=>$d3->amount_to_bill
                );
                yield new Request('GET', $uri,$billingInfo);
        }

        foreach($data4 as $d4 ){
                $billingInfo = array(
                        'id'=>$d4->id,
                        'username'=>$d4->username,
                        'mobile_number'=>$d4->mobile_number,
                        'amount_to_bill'=>$d4->amount_to_bill
                );
                yield new Request('GET', $uri,$billingInfo);
        }

        foreach($data5 as $d5 ){

                $billingInfo = array(
                        'id'=>$d5->id,
                        'username'=>$d5->username,
                        'mobile_number'=>$d5->mobile_number,
                        'amount_to_bill'=>$d5->amount_to_bill
                );
                yield new Request('GET', $uri,$billingInfo);
               
        }


        #The total time for the execution of this is   
        # 2000 * 1.6s = 3200
        # 3200/60 = 53 minutes
        # Since the data are executed asynchronously

        
    }
};

$pool = new Pool($client, $requests(100), [
    'concurrency' => 5,
    'fulfilled' => function (Response $response, $index) {
        // this is delivered each successful response
    },
    'rejected' => function (RequestException $reason, $index) {
        // this is delivered each failed request
    },
]);

// Initiate the transfers and create a promise
$promise = $pool->promise();

// Force the pool of requests to complete.
$promise->wait();



/**
 * 
 * Each of the data is executed asynchronously even in their own loop
 * ### The same approach can be used for 100000 customers, it could paginated to 20000 into each variable and then it works.
 */

?>