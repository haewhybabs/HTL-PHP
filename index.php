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
 * Libraries like GuzzlePHP, Pthreads, 
 * 
 * 
 *                               #### APPROACH ####
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


?>