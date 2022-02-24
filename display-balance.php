<?php
    session_start();

    if(!isset($_SESSION['logged']))
    {
        header('Location: sign-in.php');
        exit();
    }

    $id = $_SESSION['id'];

    if(isset($_POST['time']) || isset($_POST['begin']))
    {
        
        require_once"connect.php";

        mysqli_report(MYSQLI_REPORT_STRICT);

        try
        {
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            
            if ($connection->connect_errno!=0)
            {
                throw new Exception(mysqli->connect_errno());
            }
            else
            {   
               
                //dla tego miesiąca
                if (isset($_POST['time']) && $_POST['time'] == "current_month")
                {   
                    $current_month= date("m");
                    //wydatki
                    $current_month_expenses = $connection->query("SELECT date_of_expense, amount, name
                    FROM expenses_category_assigned_to_users, expenses 
                    WHERE EXTRACT(MONTH FROM date_of_expense) = '$current_month' AND expenses.user_id = $id 
                    AND expenses_category_assigned_to_users.user_id = expenses.user_id 
                    AND expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id
                    ORDER BY date_of_expense ASC");

                    if ( !$current_month_expenses )  throw new Exception($connection->error);
                           
                    //przychody
                    $current_month_incomes = $connection->query("SELECT date_of_income, amount, name
                    FROM incomes_category_assigned_to_users, incomes 
                    WHERE EXTRACT(MONTH FROM date_of_income) = '$current_month' AND incomes.user_id = $id 
                    AND incomes_category_assigned_to_users.user_id = incomes.user_id 
                    AND incomes_category_assigned_to_users.id = incomes.income_category_assigned_to_user_id
                    ORDER BY date_of_income ASC");

                    if ( !$current_month_incomes ) throw new Exception($connection->error);

                    //suma wydatków
                    $stmt = $connection->prepare("SELECT SUM(amount) AS sum FROM expenses 
                    WHERE EXTRACT(MONTH FROM date_of_expense) = '$current_month' AND expenses.user_id = $id  limit 1");
 
                     if($stmt->execute())
                     {
                     $result = $stmt->get_result();
                     $value = $result->fetch_object();
                     $current_month_expenses_sum = $value->sum;
                     }
                     else
                     {
                         throw new Exception($connection->error);
                     }

                     //suma przychodów
                     $stmt = $connection->prepare("SELECT SUM(amount) AS sum FROM incomes
                     WHERE  incomes.user_id = $id AND EXTRACT(MONTH FROM date_of_income) = '$current_month' limit 1");
  
                      if($stmt->execute())
                      {
                      $result = $stmt->get_result();
                      $value = $result->fetch_object();
                      $current_month_incomes_sum = $value->sum;
                      }
                      else
                      {
                          throw new Exception($connection->error);
                      }

                      //suma wydatków z podziałem na kategorie 
                      $current_month_expenses_pie_chart = $connection->query("SELECT name, SUM(amount) AS sum
                      FROM expenses_category_assigned_to_users, expenses
                      WHERE EXTRACT(MONTH FROM date_of_expense) = '$current_month' AND expenses.user_id = $id 
                      AND expenses_category_assigned_to_users.user_id = expenses.user_id 
                      AND expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id
                      GROUP BY name ORDER BY amount DESC");
  
                      if ( !$current_month_expenses_pie_chart ) throw new Exception($connection->error);

                        //suma przychodów z podziałem na kategorie 
                        $current_month_incomes_pie_chart = $connection->query("SELECT name, SUM(amount) AS sum
                        FROM incomes_category_assigned_to_users, incomes
                        WHERE EXTRACT(MONTH FROM date_of_income) = '$current_month' AND incomes.user_id = $id 
                        AND incomes_category_assigned_to_users.user_id = incomes.user_id 
                        AND incomes_category_assigned_to_users.id = incomes.income_category_assigned_to_user_id
                        GROUP BY name ORDER BY amount DESC");
    
                        if ( !$current_month_incomes_pie_chart ) throw new Exception($connection->error);

                    
                }

                //dla ubiegłego miesiąca
                 else if (isset($_POST['time']) && $_POST['time'] == "previous_month")
                 {   
                    $current_date = date("Y-m-d");
                     $previous_month= date("m",mktime(0,0,0,date("m", strtotime($current_date))-1,1,date("Y", strtotime($current_date))));
                     //wydatki
                     $previous_month_expenses = $connection->query("SELECT date_of_expense, amount, name
                     FROM expenses_category_assigned_to_users, expenses 
                     WHERE EXTRACT(MONTH FROM date_of_expense) = '$previous_month' AND expenses.user_id = $id 
                     AND expenses_category_assigned_to_users.user_id = expenses.user_id 
                     AND expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id
                     ORDER BY date_of_expense ASC");
 
                     if ( !$previous_month_expenses )   throw new Exception($connection->error);
                     
                     //przychody
                     $previous_month_incomes = $connection->query("SELECT date_of_income, amount, name
                     FROM incomes_category_assigned_to_users, incomes 
                     WHERE EXTRACT(MONTH FROM date_of_income) = '$previous_month' AND incomes.user_id = $id 
                     AND incomes_category_assigned_to_users.user_id = incomes.user_id 
                     AND incomes_category_assigned_to_users.id = incomes.income_category_assigned_to_user_id
                     ORDER BY date_of_income ASC");
 
                     if ( !$previous_month_incomes )   throw new Exception($connection->error);

                      //suma wydatków
                    $stmt = $connection->prepare("SELECT SUM(amount) AS sum FROM expenses 
                    WHERE EXTRACT(MONTH FROM date_of_expense) = '$previous_month' AND expenses.user_id = $id  limit 1");
 
                     if($stmt->execute())
                     {
                     $result = $stmt->get_result();
                     $value = $result->fetch_object();
                     $previous_month_expenses_sum = $value->sum;
                     }
                     else
                     {
                         throw new Exception($connection->error);
                     }

                     //suma przychodów
                     $stmt = $connection->prepare("SELECT SUM(amount) AS sum FROM incomes
                     WHERE  incomes.user_id = $id AND EXTRACT(MONTH FROM date_of_income) = '$previous_month' limit 1");
  
                      if($stmt->execute())
                      {
                      $result = $stmt->get_result();
                      $value = $result->fetch_object();
                      $previous_month_incomes_sum = $value->sum;
                      }
                      else
                      {
                          throw new Exception($connection->error);
                      }

                        //suma wydatków z podziałem na kategorie 
                        $previous_month_expenses_pie_chart = $connection->query("SELECT name, SUM(amount) AS sum
                        FROM expenses_category_assigned_to_users, expenses
                        WHERE EXTRACT(MONTH FROM date_of_expense) = '$previous_month' AND expenses.user_id = $id 
                        AND expenses_category_assigned_to_users.user_id = expenses.user_id 
                        AND expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id
                        GROUP BY name ORDER BY amount DESC");
    
                        if ( !$previous_month_expenses_pie_chart ) throw new Exception($connection->error);
  
                          //suma przychodów z podziałem na kategorie 
                          $previous_month_incomes_pie_chart = $connection->query("SELECT name, SUM(amount) AS sum
                          FROM incomes_category_assigned_to_users, incomes
                          WHERE EXTRACT(MONTH FROM date_of_income) = '$previous_month' AND incomes.user_id = $id 
                          AND incomes_category_assigned_to_users.user_id = incomes.user_id 
                          AND incomes_category_assigned_to_users.id = incomes.income_category_assigned_to_user_id
                          GROUP BY name ORDER BY amount DESC");
      
                          if ( !$previous_month_incomes_pie_chart ) throw new Exception($connection->error);
                 }

                  //dla bieżącego roku
                  else if (isset($_POST['time']) && $_POST['time'] == "current_year")
                  {   
                    $current_year = date("Y");
                      //wydatki
                      $current_year_expenses = $connection->query("SELECT date_of_expense, amount, name
                      FROM expenses_category_assigned_to_users, expenses 
                      WHERE EXTRACT(YEAR FROM date_of_expense) = '$current_year'
                      AND expenses.user_id = $id 
                      AND expenses_category_assigned_to_users.user_id = expenses.user_id 
                      AND expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id
                      ORDER BY date_of_expense ASC");
  
                      if ( !$current_year_expenses )   throw new Exception($connection->error);
                      
                      //przychody
                      $current_year_incomes = $connection->query("SELECT date_of_income, amount, name
                      FROM incomes_category_assigned_to_users, incomes 
                      WHERE EXTRACT(YEAR FROM date_of_income) = '$current_year' 
                       AND incomes.user_id = $id 
                      AND incomes_category_assigned_to_users.user_id = incomes.user_id 
                      AND incomes_category_assigned_to_users.id = incomes.income_category_assigned_to_user_id
                      ORDER BY date_of_income ASC");
  
                    if ( !$current_year_incomes )   throw new Exception($connection->error);

                    //suma wydatków
                    $stmt = $connection->prepare("SELECT SUM(amount) AS sum FROM expenses 
                    WHERE EXTRACT(YEAR FROM date_of_expense) = '$current_year' AND expenses.user_id = $id  limit 1");
 
                     if($stmt->execute())
                     {
                     $result = $stmt->get_result();
                     $value = $result->fetch_object();
                     $current_year_expenses_sum = $value->sum;
                     }
                     else
                     {
                         throw new Exception($connection->error);
                     }

                     //suma przychodów
                     $stmt = $connection->prepare("SELECT SUM(amount) AS sum FROM incomes
                     WHERE  incomes.user_id = $id AND EXTRACT(YEAR FROM date_of_income) = '$current_year' limit 1");
  
                      if($stmt->execute())
                      {
                      $result = $stmt->get_result();
                      $value = $result->fetch_object();
                      $current_year_incomes_sum = $value->sum;
                      }
                      else
                      {
                          throw new Exception($connection->error);
                      }

                        //suma wydatków z podziałem na kategorie 
                        $current_year_expenses_pie_chart = $connection->query("SELECT name, SUM(amount) AS sum
                        FROM expenses_category_assigned_to_users, expenses
                        WHERE EXTRACT(YEAR FROM date_of_expense) = '$current_year' AND expenses.user_id = $id 
                        AND expenses_category_assigned_to_users.user_id = expenses.user_id 
                        AND expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id
                        GROUP BY name ORDER BY amount DESC");
    
                        if ( !$current_year_expenses_pie_chart ) throw new Exception($connection->error);
  
                          //suma przychodów z podziałem na kategorie 
                          $current_year_incomes_pie_chart = $connection->query("SELECT name, SUM(amount) AS sum
                          FROM incomes_category_assigned_to_users, incomes
                          WHERE EXTRACT(YEAR FROM date_of_income) = '$current_year' AND incomes.user_id = $id 
                          AND incomes_category_assigned_to_users.user_id = incomes.user_id 
                          AND incomes_category_assigned_to_users.id = incomes.income_category_assigned_to_user_id
                          GROUP BY name ORDER BY amount DESC");
      
                          if ( !$current_year_incomes_pie_chart ) throw new Exception($connection->error);

                  }

                  //dla 'niestandardowy'
                  else if (isset($_POST['begin']))
                  {   
                      $begin = $_POST['begin'];
                      $end = $_POST['end'];
                      
                      //wydatki
                      $custom_expenses = $connection->query("SELECT date_of_expense, amount, name
                      FROM expenses_category_assigned_to_users, expenses 
                      WHERE date_of_expense BETWEEN '$begin' AND '$end' 
                      AND expenses.user_id = $id 
                      AND expenses_category_assigned_to_users.user_id = expenses.user_id 
                      AND expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id
                      ORDER BY date_of_expense DESC");
  
                      if ( !$custom_expenses )   throw new Exception($connection->error);
                      
                      //przychody
                      $custom_incomes = $connection->query("SELECT date_of_income, amount, name
                      FROM incomes_category_assigned_to_users, incomes 
                      WHERE date_of_income BETWEEN  '$begin' AND  '$end'
                       AND incomes.user_id = $id 
                      AND incomes_category_assigned_to_users.user_id = incomes.user_id 
                      AND incomes_category_assigned_to_users.id = incomes.income_category_assigned_to_user_id
                      ORDER BY date_of_income DESC");
  
                      if ( !$custom_incomes )   throw new Exception($connection->error);

                      //suma wydatków
                    $stmt = $connection->prepare("SELECT SUM(amount) AS sum FROM expenses 
                    WHERE date_of_expense BETWEEN '$begin' AND '$end' AND expenses.user_id = $id  limit 1");
 
                     if($stmt->execute())
                     {
                     $result = $stmt->get_result();
                     $value = $result->fetch_object();
                     $custom_expenses_sum = $value->sum;
                     }
                     else
                     {
                         throw new Exception($connection->error);
                     }

                     //suma przychodów
                     $stmt = $connection->prepare("SELECT SUM(amount) AS sum FROM incomes
                     WHERE  incomes.user_id = $id AND date_of_income BETWEEN '$begin' AND '$end' limit 1");
  
                      if($stmt->execute())
                      {
                      $result = $stmt->get_result();
                      $value = $result->fetch_object();
                      $custom_incomes_sum = $value->sum;
                      }
                      else
                      {
                          throw new Exception($connection->error);
                      }

                        //suma wydatków z podziałem na kategorie 
                        $custom_expenses_pie_chart = $connection->query("SELECT name, SUM(amount) AS sum
                        FROM expenses_category_assigned_to_users, expenses
                        WHERE date_of_expense BETWEEN '$begin' AND '$end' 
                        AND expenses.user_id = $id 
                        AND expenses_category_assigned_to_users.user_id = expenses.user_id 
                        AND expenses_category_assigned_to_users.id = expenses.expense_category_assigned_to_user_id
                        GROUP BY name ORDER BY amount DESC");
    
                        if ( !$custom_expenses_pie_chart ) throw new Exception($connection->error);
  
                          //suma przychodów z podziałem na kategorie 
                          $custom_incomes_pie_chart = $connection->query("SELECT name, SUM(amount) AS sum
                          FROM incomes_category_assigned_to_users, incomes
                          WHERE date_of_income BETWEEN'$begin' AND '$end' 
                          AND incomes.user_id = $id 
                          AND incomes_category_assigned_to_users.user_id = incomes.user_id 
                          AND incomes_category_assigned_to_users.id = incomes.income_category_assigned_to_user_id
                          GROUP BY name ORDER BY amount DESC");
      
                          if ( !$custom_incomes_pie_chart ) throw new Exception($connection->error);


                  }
 

                $connection->close();
            }
        }
        catch(Exception $e)
        {
            echo '<span style="color:red"> Błąd serwera! Przepraszamy za niedogodności i prosimy o przeglądanie bilansu w innym terminie!</span>';
            echo '<br />Informacja deweloperska: '.$e;
        }
    }
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Balance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="display-balance.css">
  
</head>

<body>
  

    
       
        <div class="container d-flex flex-md-row flex-column text-center">
            <div class="container d-flex justify-content-start"><h1 class="display-6">Przeglądaj bilans</h1></div>

            <div class="d-flex container flex-row justify-content-end">
            <form method="post" class="list-inline">
            <select class="form-select list-inline-item" id="time" name="time" onchange='myFunction(event)' aria-label="Default select example">
                        <option selected value="current_month"> Bieżący miesiąc </option>
                        <option value="previous_month"> Ubiegły miesiąc </option>
                        <option value="current_year"> Bieżący rok </option>
                        <option value="custom"> Niestandardowy </option>          
            </select>
          
            <button type="submit" class="list-inline-item btn p-2 fw-bold"> Przeglądaj </button>
            <a class=list-inline-item" href="main-menu.php">Wróc do menu</a>
            </form>
            </div>
        </div>

        <div class="container-fluid main d-flex flex-column align-items-center justify-content-center w-75 py-5 my-5 my-md-0 align-self-center">
        
        <?php if (isset($begin)){ ?>
            <div class="mx-auto">
                <p>Bilans od <b><?php echo $begin; ?></b>   do  <b><?php echo $end; ?></b> </p>
            </div>
        <?php } ?>


        <div class="container d-flex flex-column flex-lg-row">
            <div class="container  m-2">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col" colspan="3">Przychody</th>
                        </tr>
                        <tr>
                            <th scope="col">Data</th>
                            <th scope="col">Kwota [PLN]</th>
                            <th scope="col">Kategoria</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($current_month_incomes))
                        {
                        while($row=$current_month_incomes->fetch_assoc())
                         {  
                    ?>
                         <tr>
                            <td> <?php echo $row['date_of_income'] ?> </td>
                            <td> <?php echo $row['amount'] ?> </td>
                            <td> <?php echo $row['name'] ?> </td>
                        </tr>
                        <?php } 
                        unset($current_month_incomes);
                        }
                     ?>

                    <?php
                        if(isset($previous_month_incomes))
                        {
                        while($row=$previous_month_incomes->fetch_assoc())
                         {  
                    ?>
                         <tr>
                            <td> <?php echo $row['date_of_income'] ?> </td>
                            <td> <?php echo $row['amount'] ?> </td>
                            <td> <?php echo $row['name'] ?> </td>
                        </tr>
                        <?php } 
                        unset($previous_month_incomes);
                        }
                     ?>

                    <?php
                        if(isset($current_year_incomes))
                        {
                        while($row=$current_year_incomes->fetch_assoc())
                         {  
                    ?>
                         <tr>
                            <td> <?php echo $row['date_of_income'] ?> </td>
                            <td> <?php echo $row['amount'] ?> </td>
                            <td> <?php echo $row['name'] ?> </td>
                        </tr>

                        <?php } 
                        unset($current_year_incomes);
                        }
                     ?>
                       <?php
                        if(isset($custom_incomes))
                        {
                        while($row=$custom_incomes->fetch_assoc())
                         {  
                    ?>
                         <tr>
                            <td> <?php echo $row['date_of_income'] ?> </td>
                            <td> <?php echo $row['amount'] ?> </td>
                            <td> <?php echo $row['name'] ?> </td>
                        </tr>
                        <?php }
                        unset($custom_incomes); 
                        }
                     ?>
                       
                    </tbody>

                </table>
                <!--początek pie-chart -->
               <?php
                 if(isset($current_month_incomes_pie_chart))
                {
                    $income_pie_chart = $current_month_incomes_pie_chart;
                    unset($current_month_incomes_pie_chart);
                    $income_pie_chart_sum = $current_month_incomes_sum;
                } 
                if(isset($previous_month_incomes_pie_chart))
                {
                   $income_pie_chart = $previous_month_incomes_pie_chart;
                   unset($previous_month_incomes_pie_chart);
                   $income_pie_chart_sum = $previous_month_incomes_sum;
                }
               if(isset($current_year_incomes_pie_chart))
                {
                   $income_pie_chart = $current_year_incomes_pie_chart;
                   unset($current_year_incomes_pie_chart);
                   $income_pie_chart_sum = $current_year_incomes_sum;
                }
               if(isset($custom_incomes_pie_chart))
                {
                   $income_pie_chart = $custom_incomes_pie_chart;
                   unset($custom_incomes_pie_chart);
                   $income_pie_chart_sum = $custom_incomes_sum;
                }

                 if (isset($income_pie_chart))
             {
                $colors = ['#D295BF', '#A9CEF4', '#001D4A','#23CE6B','#F34213','#7E52A0' , '#F8F1FF', '#716A5C', '#5DB7DE','#A0CA92','#9B2226','#542344', '#FF6B6B' , '#CA6702', '#0A9396', '#005F73','#F6F930','#E9D8A6','#F72585' , '#F4C3C2', '#94D2BD', '#EE9B00','#36453B','#A37C40','#6A3937' ];
             ?>
             <div class="pie-chart-container my-5 d-flex flex-row align-items-center justify-content-center">
               <div id="income-pie-chart" >
             <style>

            #income-pie-chart {
                height: 120px;
                width: 120px;
                border-radius: 50%;
            }
            #income-pie-chart{
                background:    
                conic-gradient( 
                <?php
                $index = 0;
                $currentSum = 0;
                $income_pie_chart->data_seek(0);
                 while($row=$income_pie_chart->fetch_assoc()){  
                $percent =  (($row['sum']/$income_pie_chart_sum)*100);
                if ($index!=0) { echo ', '; } 
                echo $colors[$index]; echo ' '.$currentSum.'% ' ; echo ($currentSum+$percent).'% ';
                $currentSum += $percent;
                $index++; }
                unset($currentSum);
                unset($index);
                unset($percent);
                unset($row); ?>
                );
            }

            </style>  
               </div> 
             <!-- koniec pie-chart --> 
             <!--legenda-->
            <div id="legenda" >
                <?php 
                $index = 0;
                $income_pie_chart->data_seek(0);
                while($row=$income_pie_chart->fetch_assoc()) { ?>
                <div class="container d-flex flex-row"> 
                <div id="color-category-<?php echo $index?>" class="align-self-center">
                <style>
                #color-category-<?php echo $index ?> {
                background-color: <?php echo $colors[$index]; ?>;
                border-radius: 50%;
                height: 15px;
                width: 15px;
                }
                </style> 
                </div> 
                <?php echo '&nbsp;'.'&nbsp;'.$row['name'] ; ?> 
                <?php echo '&nbsp;'.'&nbsp;'.number_format(($row['sum']/$income_pie_chart_sum*100),2).'%' ; ?>
                </div>
               
                <?php  $index++ ;
                } ?>
            </div>
            </div> 
            <?php  
            }
            unset($income_pie_chart);
            unset($income_pie_chart_sum);
             ?>
             <!-- koniec legendy-->
             
            
        


            </div>

            <div class="container m-2">

                <table class="table">
                <thead>
                        <tr>
                            <th class="text-center" scope="col" colspan="3">Wydatki</th>
                        </tr>
                        <tr>
                            <th scope="col">Data</th>
                            <th scope="col">Kwota [PLN]</th>
                            <th scope="col">Kategoria</th>
                        </tr>
                    </thead>
                    <tbody>        
                    <?php
                        if(isset($current_month_expenses))
                        {
                        while($row=$current_month_expenses->fetch_assoc())
                         {  
                    ?>
                         <tr>
                            <td> <?php echo $row['date_of_expense'] ?> </td>
                            <td> <?php echo $row['amount'] ?> </td>
                            <td> <?php echo $row['name'] ?> </td>
                        </tr>
                        <?php } 
                        unset($current_month_expenses);
                        }
                     ?>
                         <?php
                        if(isset($previous_month_expenses))
                        {
                        while($row=$previous_month_expenses->fetch_assoc())
                         {  
                    ?>
                         <tr>
                            <td> <?php echo $row['date_of_expense'] ?> </td>
                            <td> <?php echo $row['amount'] ?> </td>
                            <td> <?php echo $row['name'] ?> </td>
                        </tr>
                        <?php } 
                        unset($previous_month_expenses);
                        }
                     ?>
                      <?php
                        if(isset($current_year_expenses))
                        {
                        while($row=$current_year_expenses->fetch_assoc())
                         {  
                    ?>
                         <tr>
                            <td> <?php echo $row['date_of_expense'] ?> </td>
                            <td> <?php echo $row['amount'] ?> </td>
                            <td> <?php echo $row['name'] ?> </td>
                        </tr>
                        <?php } 
                        unset($current_year_expenses);
                        }
                     ?>
                      <?php
                        if(isset($custom_expenses))
                        {
                        while($row=$custom_expenses->fetch_assoc())
                         {  
                    ?>
                         <tr>
                            <td> <?php echo $row['date_of_expense'] ?> </td>
                            <td> <?php echo $row['amount'] ?> </td>
                            <td> <?php echo $row['name'] ?> </td>
                        </tr>
                        <?php } 
                        unset($custom_expenses);
                        }
                     ?>
    
                    </tbody>
                </table>
                <!-- początek pie-chart -->
                <?php
               if(isset($current_month_expenses_pie_chart))
               {
                  $expense_pie_chart = $current_month_expenses_pie_chart;
                  unset($current_month_expenses_pie_chart);
                  $expense_pie_chart_sum = $current_month_expenses_sum;
               }
            if (isset($previous_month_expenses_pie_chart))
             {
                $expense_pie_chart = $previous_month_expenses_pie_chart;
                unset($previous_month_expenses_pie_chart);
                $expense_pie_chart_sum = $previous_month_expenses_sum;
             }
            if (isset($current_year_expenses_pie_chart))
             {
                $expense_pie_chart = $current_year_expenses_pie_chart;
                unset($current_year_expenses_pie_chart);
                $expense_pie_chart_sum = $current_year_expenses_sum;
             }
            if (isset($custom_expenses_pie_chart))
             {
                $expense_pie_chart = $custom_expenses_pie_chart;
                unset($custom_expenses_pie_chart);
                $expense_pie_chart_sum = $custom_expenses_sum;
             }

             if (isset($expense_pie_chart))
             {
                $colors = ['#D295BF', '#A9CEF4', '#001D4A','#23CE6B','#F34213','#7E52A0' , '#F8F1FF', '#716A5C', '#5DB7DE','#A0CA92','#9B2226','#542344', '#FF6B6B' , '#CA6702', '#0A9396', '#005F73','#F6F930','#E9D8A6','#F72585' , '#F4C3C2', '#94D2BD', '#EE9B00','#36453B','#A37C40','#6A3937' ];
             ?>
             <div class="pie-chart-container my-5 d-flex flex-row align-items-center justify-content-center">
               <div id="expense-pie-chart" >
             <style>

            #expense-pie-chart {
                height: 120px;
                width: 120px;
                border-radius: 50%;
            }
            #expense-pie-chart{
                background:    
                conic-gradient( 
                <?php
                $index = 0;
                $currentSum = 0;
                $expense_pie_chart->data_seek(0);
                 while($row=$expense_pie_chart->fetch_assoc()){  
                $percent =  (($row['sum']/$expense_pie_chart_sum)*100);
                if ($index!=0) { echo ', '; } 
                echo $colors[$index]; echo ' '.$currentSum.'% ' ; echo ($currentSum+$percent).'% ';
                $currentSum += $percent;
                $index++; }
                unset($currentSum);
                unset($index);
                unset($percent);
                unset($row); ?>
                );
            }

            </style>  
               </div> 
             <!-- koniec pie-chart --> 
             <!--legenda-->
            <div id="legenda" >
                <?php 
                $index = 0;
                $expense_pie_chart->data_seek(0);
                while($row=$expense_pie_chart->fetch_assoc()) { ?>
                <div class="container d-flex flex-row"> 
                <div id="color-category-<?php echo $index?>" class="align-self-center">
                <style>
                #color-category-<?php echo $index ?> {
                background-color: <?php echo $colors[$index]; ?>;
                border-radius: 50%;
                height: 15px;
                width: 15px;
                }
                </style> 
                </div> 
                <?php echo '&nbsp;'.'&nbsp;'.$row['name'] ; ?> 
                <?php echo '&nbsp;'.'&nbsp;'.number_format(($row['sum']/$expense_pie_chart_sum*100),2).'%' ; ?>
                </div>
               
                <?php  $index++ ;
                } ?>
            </div>
            </div> 
            <?php  
            } 
              unset($expense_pie_chart);
              unset($expense_pie_chart_sum);
             ?>
             <!-- koniec legendy-->

            </div>

        </div>
          
           
        Twój bilans wynosi:
            <?php
             if (isset($current_month_expenses_sum) && isset($current_month_incomes_sum))
             {
                 echo $current_month_incomes_sum - $current_month_expenses_sum;
                 unset($current_month_incomes_sum);
                 unset($current_month_expenses_sum);
             }
             else if(isset($previous_month_expenses_sum) && isset($previous_month_incomes_sum))
             {
                echo $previous_month_incomes_sum - $previous_month_expenses_sum;
                unset($previous_month_incomes_sum);
                unset($previous_month_incomes_sum);
             }
             else if
             (isset($current_year_incomes_sum) && isset($current_year_expenses_sum))
             {
                echo $current_year_incomes_sum - $current_year_expenses_sum;
                unset($current_year_incomes_sum);
                unset($current_year_expenses_sum);
             }
             else if(isset($custom_incomes_sum) && isset($custom_expenses_sum))
             {
                echo $custom_incomes_sum - $custom_expenses_sum;
                unset($custom_incomes_sum);
                unset($custom_expenses_sum);
             }
             ?>

    </div>

        <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Wybierz przedział czasowy</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post">
        <div class="modal-body container d-flex flex-row text-center">
            <div class="container d-flex flex-column">
            <label for="begin" class="form-label">DATA POCZĄTKOWA</label>
            <input type="date" id="begin" name="begin" onchange="checkBeginDate(event)" required>
            </div>
            <div class="container d-flex flex-column text-center">
            <label for="end" class="form-label">DATA KOŃCOWA</label>
            <input type="date" id="end" name="end" onchange="checkEndDate(event)" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
            </form>
        </div>
    </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script src="display-balance.js"></script>

</body>

</html>