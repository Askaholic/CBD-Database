<form class="" action="Cameron_test" method="post">
  <!-- <input type="text" name="firstForm" value=""> -->
  <?php
    FormBuilder::input('text', 'second_input', 'First Name');
   ?>
  <button type="submit" name="button">SubmitMe</button>
</form>


<select>
 <option value="Default">Volvo</option>
 <option value="Select 1">Saab</option>
 <option value="Select 2">Mercedes</option>
 <option value="Select 3">Audi</option>
</select>



<?php
  echo $context['something'];
 ?>
