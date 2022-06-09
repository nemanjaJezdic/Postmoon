<?php 

$user= $conn->prepare("SELECT p.*,u.id as userid,u.name as user_name,u.surname as user_surname,u.email as user_email,c.id as categoryid,c.name as category_name
                       FROM posts as p JOIN users as u ON p.user_id=u.id
                                       JOIN category as c ON p.category_id=c.id");

$user->execute();

$result = $user->fetchAll();
?>

<table class="table table-responsive">
  <thead class="thead-dark">
    <tr>
      <th scope="col">ID</th>
      <th scope="col">heading</th>
      <th scope="col">image</th>
      <th scope="col">likes</th>
      <th scope="col">name</th>
      <th scope="col">surname</th>
      <th scope="col">email</th>
      <th scope="col">category</th>
      <th scope="col">created</th>
      <th scope="col">actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($result as $res):?>
    <tr>
      <th scope="row"><?php echo $res->id?></th>
      <td><?php echo $res->heading;?></td>
      <td><img class="img-fluid"  src="/assets/img/<?php echo $res->image;?>" alt="Card image cap"></td>
      <td><?php echo $res->likes;?></td>
      <td><?php echo $res->user_name;?></td>
      <td><?php echo $res->user_surname;?></td>
      <td><?php echo $res->user_email;?></td>
      <td><?php echo $res->category_name;?></td>
      <td><?php echo $res->created;?></td>
      <td><a href="/adminpanel.php?id=5&postid=<?php echo $res->id?>">Edit</a>
       <br/> 
       <a href="/assets/models/deletepostadmin.php?postid=<?php echo $res->id?>">Delete</a></td>      
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
