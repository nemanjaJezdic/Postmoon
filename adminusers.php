<?php 

$user= $conn->prepare("SELECT u.*,c.name as country_name,c.id as country_id,r.id as roleid,r.name as role_name
                          FROM users as u JOIN countries as c ON u.country_id=c.id
                                          JOIN roles as r ON u.role_id=r.id");

$user->execute();

$result = $user->fetchAll();
?>

<table class="table table-responsive">
  <thead class="thead-dark">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">name</th>
      <th scope="col">surname</th>
      <th scope="col">email</th>
      <th scope="col">address</th>
      <th scope="col">city</th>
      <th scope="col">country</th>
      <th scope="col">zip</th>
      <th scope="col">gender</th>
      <th scope="col">role</th>
      <th scope="col">actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($result as $res):?>
    <tr>
      <th scope="row"><?php echo $res->id?></th>
      <td><?php echo $res->name;?></td>
      <td><?php echo $res->surname;?></td>
      <td><?php echo $res->email;?></td>
      <td><?php echo $res->address;?></td>
      <td><?php echo $res->city;?></td>
      <td><?php echo $res->country_name;?></td>
      <td><?php echo $res->zip;?></td>
      <td><?php echo $res->gender;?></td>
      <td><?php echo $res->role_name;?></td>
      <td><a href="/adminpanel.php?id=4&userid=<?php echo $res->id?>">Edit</a>
       <br/> 
       <a href="/assets/models/deleteuser.php?userid=<?php echo $res->id?>">Delete</a></td>      
    </tr>
    <?php endforeach;?>
  </tbody>
</table>

