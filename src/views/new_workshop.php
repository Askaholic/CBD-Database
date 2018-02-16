<form class="" action="new_workshop" method="post">
  <!-- <input type="text" name="firstForm" value=""> -->
  <?php
    FormBuilder::input('text', 'workshopName', 'Workshop Name');
   ?>
<br>
<p>What type of input is this field</p>
   <select>
    <option value="text">Text field</option>
    <option value="checkbox">Check Boxes</option>
    <option value="radio">Radio Boxes</option>
   </select>



<?php
  FormBuilder::input($value, 'workshopName', 'Workshop Name');
 ?>
 <br>
  <button type="submit" name="button">Submit</button>
</form>






<?php
  echo $context['userInput'];
 ?>
