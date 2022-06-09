<?php 

$user= $conn->prepare("SELECT *
                       FROM category");

$user->execute();

$result = $user->fetchAll();
?>

<table class="table table-responsive">
  <thead class="thead-dark">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">name</th>
      <th scope="col">actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($result as $res):?>
    <tr>
      <th scope="row"><?php echo $res->id?></th>
      <td><?php echo $res->name;?></td>     
      <td><a href="/adminpanel.php?id=6&categoryid=<?php echo $res->id?>">Edit</a> 
      <br/> 
      <a href="/assets/models/deletecategory.php?categoryid=<?php echo $res->id?>">Delete</a></td>      
    </tr>
    <?php endforeach;?>
    <td>
     <a href="/adminpanel.php?id=7">Add new Category</a></td>   
    </td>
  </tbody>
</table>