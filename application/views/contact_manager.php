<?php
$this->load->view("globales/head_contacts");
$this->load->view('globales/mensajes'); 
$url_completa = "";
if($this->uri->segment(3)){
$url_completa = "/".$this->uri->segment(3);
}
?>
<style>
.btn-danger, .btn-group>.btn:first-child{
	height: auto !important;
	padding: 10px !important;
}
input.span2{
	padding: 8px !important;
	border-radius: 0px !important;
	border: 1px solid #e8e8e8 !important;
}
input.span2:hover{
	padding: 8px !important;
	border: none !important;
}
  #RunTour2{
    display: table;
    width: 600px;
    margin: 10px auto;
    background: rgba(255,255,255,0.4);
    padding: 1em;
    box-sizing: border-box;
    text-align: justify;
    border-radius: 5px;
    position: relative;
  }
  #RunTour2 button{
    border: 0px;
    background: rgb(0,191,120);
    color: #fff;
    padding: .3em .7em;
    box-sizing: border-box;
    border-radius: 3px;
    position: absolute;
    right: 15px;
    top: 45px;}
    #tooltip-titulos{
color: red;
border-bottom: 1px dotted red;
}
#tooltip-titulos:hover{
text-decoration: none;
cursor: default;
}
#tooltip-titulos:hover:after{
display: block;
position: absolute;
width: 250px;
height: auto;
padding: .7em;
background: #fff;
content: 'El formato tiene títulos como: "nombre,indipais,telefono". Si cambias estos títulos tu archivo no se importará correctamente.';
color: #444;
font-size: 14px;
top: -25px;
left: 425px;
font-weight: 300;
-webkit-box-shadow: 1px 1px 4px 1px #DBDBDB;
box-shadow: 1px 1px 4px 1px #DBDBDB;
border: 1px solid #444;
}
#frm-add-contact, #import-csv, #import-gmail, #add-group, .float-item{

}
.NewContent{
	background: white;
	display: flex;
}

.btn{
  height: auto !important;
}
#searchBTN{
  padding: 8px;
}
</style>
<header id="header-steps">
            <div id="header-content">
            <div id="brand" class="header-element">
                  <?php 
                    // $logo = $this->specialapp->create_logo('logo_principal_mv.jpg');
                   ?>
                  <!-- <a class="brand" href="<?php //echo $logo->brand_url; ?>">
                    <img src="<?php //echo  $logo->brand_img ?>" alt="Mensajes de Voz"/>
                  </a> -->
                  <h1>Mensajes de Voz</h1>
            </div>
                <!-- Opciones de navegación -->
                <?php
                  if($this->session->userdata('logged_in')):
                ?>
              <div id="username">
              <span id="nombre-usuario"><?php echo ("<b>" . $username . "</b>"); ?></span>
                <i class="material-icons" id="ico-username">settings</i>

              </div>
                <div id="saldo">
                    <i class="material-icons" id="ico-saldo">monetization_on</i>
                    <span id="valor-saldo"><?php echo  number_format($credits,0,",",".");?></span>
                    <span id="texto-saldo">créditos</span>
                </div>
                <?php 
                  endif;
                 ?>
      </div><!-- /header-content -->
</header>
<?php if($this->session->userdata('logged_in')){ ?>
<div class="newNav" id="newNav">
  <ul>
    <li class="item-main-menu"><a href="<?php echo base_url('campaign'); ?>"><i class="material-icons">folder_open</i><span>Campañas</span></a></li>
    <li class="item-main-menu"><a href="<?php echo base_url('contacts/contact_manager'); ?>"><i class="material-icons">account_circle</i><span>Contactos</span></a></li>
   <?php if(!$this->permissions->get('deny_marketplace')):?>
    <li class="item-main-menu"><a href="<?php echo base_url('marketplace'); ?>"><i class="material-icons" id="material-module">view_module</i><span>Aplicaciones</span></a></li>
  <?php endif; ?>
  <li class="item-main-menu"><a href="<?php echo base_url('payment'); ?>"><i class="material-icons">monetization_on</i> <span>Recargar Saldo</span></a></li>
  <?php if($this->session->userdata("user_id")==KKATOO_USER):?>
     <li class="item-main-menu"><a href="<?php echo base_url('user/apps'); ?>"><i class="material-icons">check_circle</i> <span>Admin App</span></a></li>
     <li class="item-main-menu"><a href="<?php echo base_url('admin/recents/'); ?>"><i class="material-icons">check_circle</i> <span>Supe Apps</span></a></li>
     <li class="item-main-menu"><a target="_blank" href="<?php echo base_url("wizard/newapp/subs"); ?>"><i class="material-icons">check_circle</i> <span>App Sus</span></a></li>
     <li class="item-main-menu"><a target="_blank" href="<?php echo base_url("wizard/newapp/dif"); ?>"><i class="material-icons">check_circle</i> <span>App Dif</span></a></li>
    <?php endif; ?>
    <li><a href="<?php echo base_url("login/logout"); ?>"><i class="material-icons">power_settings_new</i> <span>Salir</span></a></li>
    <?php }else{
          ?>
    <li><a href="<?php echo base_url("login/login"); ?>?rtrn=<?php echo str_replace("/".KKATOO_ROOT."/","",$_SERVER['REQUEST_URI']); ?>"><?php echo $this->lang->line('login'); ?></a></li>
    <li><a href="<?php echo base_url("login/register"); ?>"><?php echo $this->lang->line('register'); ?></a></li>
    <?php
      } 
    ?>                                
  </ul>
</div>


<!--********************************************************************************* -->
<!-- CONTAINER DE LA APLICACION -->

<div id="RunTour2">
	<p>Aprende rápidamente cómo comenzar a usar el administrador de contactos. Haz click en el botón Iniciar Tour para guiarte paso a paso</p>
	<button id="IniciarTour">Iniciar Tour</button>
</div>
<div id="app-container" class="container">

<!-- HEADER DE LA APLICACION -->
<div class="row">
<div class="span12" id="app-header">
<div class="row">
<div class="span6" id="title-and-links">
<!-- <h2>Contactos</h2> -->
<div class="span3" id="links">
<div id="add-ico"><a href="#AGREGAR CONTACTO" data-intro="Aquí también puedes agregar tu contacto manualmente" title="Agregar nuevo contacto"><span>Crear contacto</span><i class="material-icons">person_add</i></a></div>
<div id="import-csv-ico"><a href="javascript:;" title="Importar contactos de excel" data-step="2" data-intro="Haciendo click en este botón encontrarás el instructivo para importar tu base de datos desde excel"><span>Subir CSV</span><i class="material-icons">file_upload</i></a></div>
<!-- <div id="import-gmail-ico"><a href="#IMPORTAR DE GMAIL" title="Importar contactos de Gmail"></a></div> -->
</div><!-- #links -->
</div> <!-- #title-and-links -->

<div class="span6" id="search">
<div class="input-append">
<?php
$attributes = array('method'=>'get');
echo form_open('contacts/contact_manager'.$url_completa, $attributes);
?>
<input name="q" type="text" id="cajonBuscarContactos" placeholder="Filtrar contactos...">
<button type="submit" id="searchBTN" class="btn" type="button">Buscar</button>
</form>
</div>
</div> <!-- #search -->

</div> <!-- .row -->
</div> <!-- #app-header -->
</div> <!-- .row -->



<!-- FLOTANTE PARA AGREGAR ITEM -->
<?php
$attributes = array('class'=>'form-horizontal','id' => 'frm-add-contact', 'style' => 'display:none;');
echo form_open('contacts/add_contact', $attributes);
?>
<div id="add-img">
<a href="#SUBIR IMAGEN" id="item-img-up">
<img src="<?php echo base_url('assets/img/overlay-change-photo.png')?>" id="overlay-change-photo" style="display:none">
<img src="<?php echo base_url('assets/img/users/ico-usr-generic-medium.png')?>" alt="">
</a>
</div>


<div class="frm-legend">DATOS BÁSICOS DEL CONTACTO</div>
<div class="control-group">
<label class="control-label" for="txt-name" > </label>
<div class="controls">
<input  class="span4" type="text" id="txt-name" name="name" required placeholder="NOMBRE COMPLETO">
</div>
</div>
<div class="control-group">
<label class="control-label" for="cbo-groups" > </label>
<div class="controls">
<select class="span2" required onchange="cambiarCiudad(this.value)" id="cbo-country" name="indi_pais">
<option  disabled selected style='display:none;'>PAIS</option>
<?php
//echo var_dump($country);
if(!empty($country)):
foreach($country as $pais):
?>
<option value="<?php echo $pais->id; ?>"><?php echo $pais->name; ?></option>
<?php
endforeach;
endif;
?>
</select> <!--/ 
<input type="text" name="indi_area" placeholder="Indi Area" />
<select class="span2" id="cbo-city" name="indi_area"> 
<option value="" disabled selected style='display:none;'>CIUDAD</option>
</select> -->
</div>
</div>

<div class="control-group">
<label class="control-label" for="phone"> </label>
<div class="controls">
<input  class="span4" type="text" id="txt-phone" pattern="[0-9]+" title="Teléfono sin espacios" required name="phone" placeholder="TELÉFONO">
</div>
</div>
<?php if(!empty($fields)): ?>
<div class="frm-legend">DATOS ADICIONALES <span class="frm-sub-legend">(Necesarios para esta aplicación)</span></div>
<?php endif; ?>

<?php
if(!empty($fields)):
	foreach($fields as $field):
		switch ($field->tipo) {
			case 1:
			$tipo = 'number';
			break;
			case 2:
			$tipo = 'text';
			break;
			case 3:
			$tipo = 'date';
			break;
			case 4:
			$tipo = 'text';
			break;
		}

		?>

<div class="control-group">
<label class="control-label" for="<?php echo $field->name_fields; ?>" ><?php echo $field->name; ?>: </label>
<div class="controls">
<?php 
if($tipo == 'dropdown'){
echo $dropdown;
}elseif($tipo=='date'){
?>
<input class="span4 calendario" type="text" name="<?php echo $field->name_fields; ?>" placeholder="dd/mm/yyyy">
<?php
}else{
?>
<input class="span4" type="<?php echo $tipo; ?>" id="txt-due-date" name="<?php echo $field->name_fields; ?>" placeholder="">
<?php 
}
?>
</div>
</div>

<?php
endforeach;
endif;
?>



<div class="control-group">
<label class="control-label" for="btn-save" ></label>
<div class="controls">
<input class="btn" type="submit" name="btn-save" value="GUARDAR">
</div>
<?php if(!empty($id_wapp)): ?>
<input type="hidden" name="id_wapp" value="<?php echo $id_wapp; ?>" />
<?php endif; ?>
<input type="hidden" name="id_group" value="<?php echo $this->uri->segment(3); ?>" />
</div>

</form> <!-- #frm-add-contact -->


<!-- FLOTANTE PARA IMPORTAR ARCHIVO CSV -->
<div id="import-csv" style="display: none">
<h4 id="title-import-csv">
PASOS PARA UNA CORRECTA IMPORTACIÓN DE ARCHIVO CSV
</h4>
<div id="pasos-import-csv">
<ol id="lista-pasos-import-csv">
<?php 
$id_wapp_var = "";

if(!empty($id_wapp)){
$id_wapp_var = "/".$id_wapp;
}
?>
<li><a id="descargarCSV"  href="<?php echo base_url("contacts/generate_csv".$id_wapp_var); ?>"> DESCARGA</a> el formato CSV para importar  tus contactos como una base de datos de excel.</li>
<li style="position:relative;">Diligencialo en excel sin cambiarle los <a id="tooltip-titulos">títulos</a> y guardalo en tu equipo.</li>
<li>Sube  el archivo con todos los dato completados, presionando el botón seleccionar archivo y finalmente importar.</li>
<li style="color:red;">Los campos Nombre, Indicativo de país y Teléfono son obligatorios.</li>
</ol>
</div>
<?php

$attributes = array('method'=>'post', 'class'=>'FormImportCSV');
echo form_open_multipart('contacts/add_csv_contact'.$url_completa, $attributes);
?>
<div id="contenedorImportCSV">
<input type="file" name="contacts_archive" id="file-upload" title="" value="SUBIR ARCHIVO CSV" />
<?php if(!empty($id_wapp)): ?>
<input type="hidden" name="id_wapp" value="<?php echo $id_wapp; ?>" />
<?php endif; ?>
<input type="hidden" name="id_group" value="<?php echo $this->uri->segment(3); ?>" />
<!-- <div class="controls"> -->
<input class="btn" type="submit" name="btn-save" value="Subir" id="btnImportarCSV">
</div>
<!-- </div> -->
</form>

</div>

<!-- CUERPO DE LA APLICACION -->
<div class="row NewContent" id="content">
<!-- AREA DE GRUPOS -->
<div class="span3" id="groups">
<!-- <div id="groups-title">
<h3>Grupos</h3>
</div> -->
<div id="groups-add-ico" data-step="5" data-intro="Haz click aquí para crear un nuevo grupo."><a href="#AGREGAR GRUPO" title="Crear nuevo grupo"><span>Nuevo grupo</span><i class="material-icons">add</i></a></div>


<!-- FLOTANTE AGREGAR GRUPO -->
<?php
$attributes = array('id' => 'add-group', 'style' => 'display:none;');
echo form_open('contacts/add_group', $attributes);
?>

<label>Nombrar grupo</label>
<input type="text" name="name" class="span2">
<input type="hidden" name="id_group" value="<?php echo $this->uri->segment(3); ?>">
<button type="submit" class="btn">crear</button>
</form>

<div id="groups-list">
<!-- ITEM DE GRUPOS (SE REPITE SEGUN EL # DE GRUPOS) -->
<div class="group-contact-item">
<div class="groups-ico"><a data-step="6" data-intro="Cada que subas una base de datos se te creará un nuevo grupo, puedes editar su nombre o borrarlo en el momento en que lo necesites" <?php if($this->uri->segment(3) == ""): ?>class="selected" <?php endif; ?> href="<?php echo base_url('contacts/contact_manager/');  ?>"><i class="material-icons">group_add</i></a></div>
<div class="groups-name">TODOS <span class="group-count"></span></div>
</div>
<!-- FIN ITEM DE GRUPOS -->
<?php if(!empty($groups)): ?>
<?php foreach($groups as $group): ?>
<div class="group-contact-item">
<div class="closegroup">
<a href="<?php echo base_url("contacts/delete_contact_group/".$group->id); ?>"><i class="material-icons group-actions">delete_forever</i></a>
</div>

<div class="groups-ico"><a <?php if($group->id == $this->uri->segment(3)): ?>class="selected" <?php endif; ?>href="<?php echo base_url('contacts/contact_manager/'.$group->id);  ?>"><i class="material-icons">group_add</i></a></div>
<div class="groups-name">
<div class="editar_g" id="<?php echo $group->id; ?>"><?php echo $group->name; ?></div>
<a href="javascript:void(0);" style="margin-top: 4px;" class="edit_btn btn">Editar</a>
<span class="group-count"></span>
</div>
</div>	              
<?php endforeach; ?>
<?php endif; ?>
</div>

</div> <!-- #groups -->

<!-- AREA DE LISTADO DE ITEMES -->


<!-- FORMULARIO PARA ELIMINAR EN MASA -->
<div class="span9" id="items-data">
<div id="items-title">
<!-- <h3><span class="items-count"><?php echo (!empty($total))?$total:0; ?> contactos encontrados</span> -->
<?php if($url): ?><a href="<?php echo base_url("apps/".$url); ?>" class="btn btn-warning return-link">Volver a aplicación</a><?php endif; ?>
</h3>
<div id="mass-action-buttons" class="mass-btn" style="display: none">
<div id="cbo-action_options">
<?php 
$group = $this->uri->segment(3);
if(is_numeric($group)){
?>
<!-- DROPDOWN PARA MOVER CONTACTOS -->
<div class="btn-group" id="mover_a">
<a class="btn dropdown-toggle btn-small" data-toggle="dropdown" href="#">
<i class="material-icons group-actions">open_with</i>&nbsp;&nbsp;Mover a&nbsp;&nbsp;<i class="material-icons group-actions">arrow_drop_down</i>
</a>
<ul class="dropdown-menu">
<?php if(!empty($groups)): ?>
<?php foreach($groups as $group): ?>
<li><a class="btn-event-to-contact" data-event="apply" 
data-value="<?php echo $group->id; ?>" 
data-action="mover"
href="javascript:;"><?php echo $group->name; ?></a>
</li>
<?php endforeach; ?>
<?php endif; ?>
</ul>
</div> <!--  btn-group #mover_a -->
<?php } ?>

<!-- DROPDOWN PARA COPIAR CONTACTOS -->
<div class="btn-group" id="copiar_a">
<a class="btn dropdown-toggle btn-small" data-toggle="dropdown" href="#">
<i class="material-icons group-actions">content_copy</i>&nbsp;&nbsp;Copiar a&nbsp;&nbsp;<i class="material-icons group-actions">arrow_drop_down</i>
</a>
<ul class="dropdown-menu">
<?php if(!empty($groups)): ?>
<?php foreach($groups as $group): ?>
<li><a class="btn-event-to-contact" data-event="apply" 
data-value="<?php echo $group->id; ?>" 
data-action="copiar"
href="javascript:;"><?php echo $group->name; ?></a>
</li>
<?php endforeach; ?>
<?php endif; ?>
</ul>
</div> <!--  btn-group #copiar_a -->


<!-- DROPDOWN PARA ASIGNAR PAIS -->
<div class="btn-group" id="asignar_pais">
<a class="btn dropdown-toggle btn-small" data-toggle="dropdown" href="#">
<i class="material-icons group-actions">place</i>&nbsp;&nbsp;Asignar Pais&nbsp;&nbsp;<i class="material-icons group-actions">arrow_drop_down</i>
</a>
<ul class="dropdown-menu ">
<?php
if(!empty($country)):
foreach($country as $pais): ?>
<li><a class="btn-event-to-contact" data-event="apply" 
data-value="<?php echo $pais->phonecode; ?>" 
data-action="asignar"
href="javascript:;"><?php echo $pais->name; ?></a>
</li>
<?php
endforeach;
endif; ?>
</ul>
</div> <!--  btn-group #asignar_pais -->

<!-- BOTON PARA BORRAR CONTACTOS -->
<a class="btn btn-small btn-danger btn-event-to-contact delete-contact del-btn"  style="display: none" data-event="delete" href="javascript:;"><i class="material-icons group-actions">delete_forever</i>&nbsp;&nbsp;Borrar</a>

<!-- BOTON PARA BORRAR CONTACTOS -->
<?php 
$group = $this->uri->segment(3);
$group = (!empty($group))?$this->uri->segment(3):'';
?>
<a class="btn btn-small btn-danger btn-event-to-contact delall-btn" data-event="deleteall" style="display: none" data-event="delete" href="<?php echo base_url('contacts/delete_all/'.$group); ?>"><i class="material-icons group-actions">delete_forever</i>&nbsp;&nbsp;Borrar Todos</a>

</div> <!-- cbo-action_options -->



</div> <!-- mass-action-buttons -->


<div class="clearfix"></div>
</div>


<table class="table-striped table-hover" id="tbl-items">
<thead>
<tr>
<th><input type="checkbox" class="check-all" data-step="4" data-intro="Selecciona todos los contactos para copiar a un nuevo grupo, asignarles un país o borrarlos masivamente."></th>
<th>Nombre</th>
<th>Teléfono</th>
<?php
if($ref_app):
if(!empty($fields)):
foreach($fields as $field):
echo '<th style="display:none">'.$field->name.'</th>';
endforeach;
endif;
endif;
?>

<th>Acción</th>
</tr>
</thead>
<tbody class="contactostbody">
<!-- ITEM DE CONTACTO (SE REPITE SEGUN EL # DE CONTACTOS) -->
<?php
$attributes = array('id' => 'remove_batch', 'class'=>'contactform', 'name'=>'contactform',  'style' => 'display:none;');
echo form_open('', $attributes);
?>
<input type="hidden" name="id_group" value="<?php echo $this->uri->segment(3) ?>" />
<?php
//print_r($contacts);
if($contacts):
foreach($contacts as $contact):

?>
<tr class="contact_paginate">
<td>
<input type="checkbox"  class="contact-select" name="valores[]" value="<?php echo $contact["id"]; ?>">
</td>
<td class="left-align" ><i class="material-icons" style="vertical-align: middle !important;">person</i><span id="name_<?php echo $contact["id"]; ?>"><?php echo $contact["name"]; ?></span></td>
<td><span id="phone_<?php echo $contact["id"]; ?>"><?php echo $contact["phone"]; ?></span></td>

<?php
if($ref_app):
if(!empty($fields)):
foreach($fields as $field):
if(isset($contact[$field->name_fields])){
$fieldSaved = $contact[$field->name_fields];
}else{
$fieldSaved = '&nbsp';
}

echo '<td style="display:none;" class="'.$field->name_fields.'_'.$contact["id"].'">'.$fieldSaved.'</td>';
endforeach;	
endif;
endif;
?>


<td data-step="3" data-intro="Aquí podrás editar, ver el detalle o eliminar tu contacto">
<div class="item-edit-ico"><a href="javascript:;" title="Editar contacto" class="btn-editar" data-id="<?php echo $contact["id"]; ?>"><i class="material-icons">mode_edit</i></a></div>
<div class="item-view-ico"><a href="javascript:;" title="Ver detalle de contacto" data-id="<?php echo $contact["id"]; ?>"><i class="material-icons">play_arrow</i></a></div>
<div class="item-delete-ico"><a title="Eliminar contacto" href="<?php echo base_url('contacts/delete_contact/'.$contact["id"].'/'.$this->uri->segment(3)); ?>"><i class="material-icons">delete_forever</i></a></div>
</td>
</tr>

<?php endforeach; ?>
<?php endif; ?>
<input type="hidden" name="id_group" value="<?php echo $this->uri->segment(3) ?>" />
<input type="hidden" name="pais_contact" value="" />
<input type="hidden" name="accion_masive" value="" />
<input type="hidden" name="grupos_cambiar" value="" />
</form>


<!-- ESTO DEBERIA CREARSE CON AJAX PARA NO SOBRECARGAR LA PAGINA WEB -->
<!-- FLOTANTE EDITAR ITEM -->
<tr class="edit-item" style="display:none">
<td colspan="7">
<?php
$attributes = array('id' => 'remove_batch');
echo form_open('contacts/edit_contact_user', $attributes);
?>
<div class="float-item">
<a href="#CAMBIAR IMAGEN" class="item-img"  style="display:">
<img src="<?php echo base_url()?>assets/img/overlay-change-photo.png" class="overlay-change-photo" style="display:none">
<img src="<?php echo base_url()?>assets/img/users/ico-usr-generic-medium.png" alt="">
</a> 
<div class="frm-legend">DATOS BÁSICOS DEL CONTACTO</div>
<div class="basic-data-edit">
<div class="edit-nombre">
<input  class="span4" type="text" id="txt-name" name="name" placeholder="NOMBRE COMPLETO">
</div>   
<div class="edit-country-city">
<div class="controls">
<select class="span2" onchange="cambiarCiudadEditar(this.value,0)" id="cbo-country" name="indi_pais">
<option  disabled selected style='display:none;'>PAIS</option>
<?php
//echo var_dump($country);
if(!empty($country)):
foreach($country as $pais):
?>
<option value="<?php echo $pais->id; ?>"><?php echo $pais->name; ?></option>
<?php
endforeach;
endif;
?>
</select> <!-- / 
<select class="span2 cbo-city" id="cbo-city"  name="indi_area"> 
<option value="" disabled selected style='display:none;'>CIUDAD</option>
</select>-->
</div>
</div>
<div class="edit-phone">
<input  class="span4" type="text" id="txt-phone" pattern="[0-9]+" title="Teléfono sin espacios" name="phone" placeholder="TELÉFONO">
</div>
</div>
<?php if(!empty($fields)): ?>
<div class="frm-legend">DATOS ADICIONALES</div>
<div class="additional-data-edit">
<?php


foreach($fields as $field):
switch ($field->tipo) {
case 1:
$tipo = 'number';
break;
case 2:
$tipo = 'text';
break;
case 3:
$tipo = 'date';
break;
case 4:
$tipo = 'text';
// $the_array = json_decode($field->default);
// $the_new_array = array();
// $the_new_array = array();
// foreach($the_array as $arr){
// $the_new_array[$arr]=$arr;
// }
// $dropdown = form_dropdown($field->name_fields, $the_new_array, '', 'id="'.$field->name_fields.'"');
break;
}
//min="1"
?>

<div class="control-group">
<label class="control-label" for="<?php echo $field->name_fields; ?>" ><?php echo $field->name; ?>: </label>
<div class="controls">

<?php 
if($tipo == 'dropdown'){
echo $dropdown;
}elseif($tipo == 'date'){
?>
<input class="span4 editar-<?php echo $field->name_fields; ?> calendario_edit" type="text" name="<?php echo $field->name_fields; ?>" placeholder="dd/mm/yy">
<?php
}else{
?>
<input  class="span4 editar-<?php echo $field->name_fields; ?>" type="<?php echo $tipo; ?>" id="txt-due-date" name="<?php echo $field->name_fields; ?>" placeholder="">
<?php 
}
?>
</div>
</div>

<?php
endforeach;

?>
</div>
</div>
<?php endif; ?>
<div class="control-group">
<label class="control-label" for="btn-save" ></label>
<div class="controls">
<input class="btn" type="submit" name="btn-save" value="GUARDAR">
</div>
<?php if(!empty($id_wapp)): ?>
<input type="hidden" name="id_wapp" value="<?php echo $id_wapp; ?>" />
<?php endif; ?>
<input type="hidden" name="id_group" value="<?php echo $this->uri->segment(3); ?>" />
<input type="hidden" class="id_contact" name="id_contact" value="" />
</div>

</form>
</td>
</tr>
<!-- FIN FLOTANTE EDITAR ITEM -->



<!-- FLOTANTE VER ITEM -->

<tr class="view-item" style="display:none">
<td colspan="6">
<div class="float-item">
<a href="#CAMBIAR IMAGEN" class="item-img"  style="display:">
<img src="<?php echo base_url('assets/img/overlay-change-photo.png')?>" class="overlay-change-photo" style="display:none">
<img src="<?php echo base_url('assets/img/users/ico-usr-generic-medium.png')?>" alt="">
</a> 
<div class="frm-legend">DATOS BÁSICOS DEL CONTACTO</div>
<div class="basic-data-edit">
<div class="edit-nombre"></div>   
<div class="edit-country-city"></div>
<div class="edit-phone"></div>
</div>
<?php if(!empty($fields)): ?>
<div class="frm-legend">DATOS ADICIONALES</div>
<div class="additional-data-edit">
<?php


foreach($fields as $field):

?>
<div class="edit-<?php echo $field->name_fields; ?>"><?php echo $field->name; ?>: <?php echo $field->name_fields; ?></div>

<?php endforeach; ?>
</div>
<?php endif; ?>
</div>
</td>
</tr>
<!-- FIN FLOTANTE EDITAR ITEM -->


<!-- FIN ITEM DE CONTACTO -->



</tbody>
</table>
<?php  
$currentPage 	= ($this->uri->segments[count($this->uri->segments)-1]=="pages")?end($this->uri->segments):1;
$current_uri 	= $this->uri->uri_string();
$uris 				= explode('/', $current_uri);
array_pop($uris);
$theuri 			= ($this->uri->segments[count($this->uri->segments)-1]=="pages")?implode('/', $uris):$current_uri.'/pages';
$theuri				= base_url().$theuri;
?>

<!-- PAGINACION (EN CASO DE NECESITARSE) -->
<div class="pagination pagination-centered">
<ul>

</ul>
</div>

</div> <!-- #items-data LISTADO DE ITEMES -->

</div> <!-- .row  #content -->

</div> <!-- #app-container -->


<!-- Le javascript -->
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
script src="//code.jquery.com/jquery-1.11.3.min.js"></script
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script> -->
<script src="<?php echo base_url('assets/js/wizard/jquery.simplePagination.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.jeditable.mini.js')?>"></script>
<script src="<?php echo base_url('assets/js/mgrs-ini.js')?>"></script>

<script>
$(document).ready(function(){
    $( '#RunTour2').on('click', function(){
    javascript:introJs().start();   
    });
});
var arr_campos_dinamicos = new Array();

$( ".calendario" ).datepicker({ dateFormat: "dd/mm/yy" });

<?php
if($fields):
foreach($fields as $field):
?>
arr_campos_dinamicos.push(Array("<?php echo $field->name_fields; ?>","<?php echo $field->name ; ?>"));
<?php endforeach; endif; ?>


$(".btn-event-to-contact").on('click', function(event){

var form   = $("form[name='contactform']");

var tipo   = $(this).data("event");
var value  = $(this).data("value");
var accion = $(this).data("action")

//var real_action = $("#cbo-accion").val();
//var grupo = $("#grupos").val();

var action = "";
switch(tipo){
case "apply":
switch(accion){
case "asignar":
action = "<?php echo site_url('contacts/batch_add_pais_to_contacto') ?>";
$("input[name='pais_contact']").val(value);
$("input[name='accion_masive']").val(accion);
form.attr("action", action);
form.submit();
event.preventDefault();
break;

case "mover":
action = "<?php echo site_url('contacts/move_user_group') ?>";
$("input[name='grupos_cambiar']").val(value);
$("input[name='accion_masive']").val(accion);
form.attr("action", action);
form.submit();
event.preventDefault();
break;

case "copiar":
action = "<?php echo site_url('contacts/copy_user_group') ?>";
$("input[name='grupos_cambiar']").val(value);
$("input[name='accion_masive']").val(accion);
form.attr("action", action);
form.submit();
event.preventDefault();
break;
} //fin del switch de accion
break;

case "delete":
var answer = confirm("<?php echo $this->lang->line('deletecontact'); ?>")
if (answer){
action = "<?php echo site_url('contacts/batch_delete_contact') ?>";
form.attr("action", action);
form.submit();
event.preventDefault();
//do nothing
}
else{
event.preventDefault();
return false;
}
break;

case "deleteall":
var answer = confirm("<?php echo $this->lang->line('deleteallcontacts'); ?>");
if(!answer){
event.preventDefault();
return false;
}
break;
}
//form.attr("action", action);
//form.submit();
//event.preventDefault();
});


$('.item-delete-ico').on('click', function(event){
var answer = confirm("<?php echo $this->lang->line('deletecontact'); ?>")
if (answer){
//do nothing
}
else{
event.preventDefault();
}
});

$('.closegroup').on('click', function(event){
var answer2 = confirm("¿Estás seguro que deseas borrar el grupo?")
//var answer2 = confirm("<?php echo $this->lang->line('deletecontact'); ?>")
if (answer2){
//do nothing
}
else{
event.preventDefault();
}
});
/*
<div class="closegroup">
<a href="<?php //echo base_url("contacts/delete_contact_group/".$group->id); ?>">x</a>
</div>
<div class="item-delete-ico">
<a href="<?php //echo base_url('contacts/delete_contact/'.$contact["id"].'/'.$this->uri->segment(3)); ?>"></a>
</div>
*/	
$(document).ready(function() {
// EDITAR GRUPOS.
$('.editar_g').editable('<?php echo site_url('contacts/update_name_group') ?>', {
type     : 'text',
width    : 100,
style    : 'display: inline',
'onblur' : 'submit',
callback : function(value, settings) {
$(this).next('a').show();
}
});

$('.edit_btn').on('click', function(event){
$(this).hide().prev().trigger('click');
});
});

//PAGINATION PARA LOS CONTACTOS.

window.totalPages = <?php echo (!empty($total))?$total:1; ?>;
window.page = <?php echo $currentPage; ?>;

<!-- PAGIONATIONS -->

var pagination_ul = $('.pagination ul');
var numItemsToShow = <?php echo PAGINATION; ?>;
var numItems = window.totalPages;
var numPages = Math.ceil(numItems/numItemsToShow);


function redraw_pagination(){
numItemsToShow = <?php echo PAGINATION; ?>;
numItems = window.totalPages;
numPages = Math.ceil(numItems/numItemsToShow);

redraw_one();
}

function redraw_one(){
pagination_ul.pagination('destroy');
made_pagination();
}


function made_pagination(){

pagination_ul.pagination({
items: numItems,
itemsOnPage: numItemsToShow,
currentPage: window.page,
hrefTextPrefix: String('<?php echo $theuri; ?>/')
});
}


made_pagination();

</script>
</body> 


</html>