<div>
	<div>
		<h3>Contacts
		<a class="btn btn-success btn-mini" href="<?php echo $this->Html->url(array('controller'=>'contacts','action'=>'add')); ?>"><i class="icon-plus"></i></a>
		<?php echo $this->Form->create('Contact', array('url' => array_merge(array('action' => 'index'), $this->params['pass']),'class'=>'navbar-search pull-right')); ?>
	  		
	  	<?php echo $this->Form->end(); ?>	
		</h3>
		<?php if($searched): 
		$search_args = $this->passedArgs; ?>
		<div class="filter-result-box alert alert-info" >
			<?php if(!empty($search_args['search_name'])): ?>
			<strong>Name: </strong><span><?php echo $search_args['search_name']; ?></span>
			<?php endif; ?>
			<?php if(!empty($search_args['search_city'])): ?>
			<strong>City: </strong><span><?php echo $search_args['search_city']; ?></span>
			<?php endif; ?>
			<?php if(!empty($search_args['search_company'])): ?>
			<strong>Company: </strong><span><?php echo $search_args['search_company']; ?></span>
			<?php endif; ?>
			<?php if(!empty($search_args['search_phone'])): ?>
			<strong>Phone: </strong><span><?php echo $search_args['search_phone']; ?></span>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<div class="filter-box" style="display:none">
			<?php echo $this->Form->create('Contact', array('url' => array_merge(array('action' => 'index'), $this->params['pass']),'class'=>'form-inline')); ?>
			<fieldset>
			<legend>Filters</legend>
				
				<?php echo $this->Form->input('search_name',array('div'=>false,'class'=>'span2','label'=>'Name ','placeholder'=>'Name')); ?>
				<?php echo $this->Form->input('search_city',array('div'=>false,'class'=>'span2','label'=>'City ','placeholder'=>'City')); ?>
				<?php echo $this->Form->input('search_company',array('div'=>false,'class'=>'span2','label'=>'Company ','placeholder'=>'Company')); ?>
				<?php echo $this->Form->input('search_phone',array('div'=>false,'class'=>'span2','label'=>'Phone ','placeholder'=>'Phone')); ?>
				<?php echo $this->Form->input('search_email',array('div'=>false,'class'=>'span2','label'=>'Email ','placeholder'=>'Email')); ?>
				<?php echo $this->Form->submit('Filter',array('div'=>false,'class'=>'btn btn-info')); ?>
			</fieldset>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
	
	<div class="pagination pagination-centered pagination-mini">
	  <ul>
		<?php echo $this->Paginator->prev(''); ?>
		<?php echo $this->Paginator->numbers(array('first' => 2, 'last' => 2));?>
		<?php echo $this->Paginator->next(''); ?>
	  </ul>
	</div>
	<table class="table table-bordered table-hover table-striped table-striped-warning">
		<thead>
		<?php
			echo $this->Html->tableHeaders(array(
					array('First Name'=>array('class'=>'info')),
					array('Last Name'=>array('class'=>'info')),
					array('Status'=>array('class'=>'warning')),
					array('Company'=>array('class'=>'warning')),
					array('City'=>array('class'=>'warning')),
					array('<i class="icon-mobile-phone"></i> Phone #'=>array('class'=>'warning')),
					array('<i class="icon-envelope"></i> E-mail'=>array('class'=>'warning')),
					array('Action'=>array('class'=>'warning'))));
		?>
		</thead>
		<tbody>
		<?php if(empty($contacts)): ?>
		<tr>
			<td colspan="8" class="striped-info">No record found.</td>
		</tr>
		<?php endif; ?>
		<?php foreach ($contacts as $contact):?>
		<tr>
			<td class="striped-info"><a href="<?php echo $this->Html->url(array('controller' => 'contacts', 'action' => 'view', $contact['Contact']['id'])); ?>">
			<?php
			if($contact['Contact']['photo']){
				echo $this->Html->image('../files/contact/photo/'.$contact['Contact']['photo_dir'].'/thumb_'.$contact['Contact']['photo'],array('class'=>'','width'=>'20px','height'=>'30px'));
			}
			else {
				echo $this->Html->image('no-picture.png',array('class'=>'','width'=>'20px','height'=>'30px'));
			}
			 ?>&nbsp;<?php echo $contact['Contact']['first_name']; ?></a></td>
			<td class="striped-info"><a href="<?php echo $this->Html->url(array('controller' => 'contacts', 'action' => 'view', $contact['Contact']['id'])); ?>"><?php echo $contact['Contact']['last_name']; ?></a></td>
			<td>
			<?php
				if ($contact['ContactStatus']['name']=='Lead'){
					
					echo '<span class="label label-important">' . $contact['ContactStatus']['name'] . '</span>';
				}
				else if($contact['ContactStatus']['name']=='Opportunity'){
					echo '<span class="label label-warning">' . $contact['ContactStatus']['name'] . '</span>';
					
				}
				else if($contact['ContactStatus']['name']=='Account'){
					echo '<span class="label label-success">' . $contact['ContactStatus']['name'] . '</span>';
				}
			?>
			</td>
			<td><?php echo $contact['Contact']['company']; ?></td>
			<td><?php echo $contact['Contact']['city']; ?></td>
			<td><?php echo $contact['Contact']['phone']; ?></td>
			<td><?php echo $contact['Contact']['email']; ?></td>
			<td>
				<a href="<?php echo $this->Html->url(array('controller' => 'contacts', 'action' => 'edit', $contact['Contact']['id'])); ?>"><i class="icon-edit"></i></a>
			</td>
		</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<div class="pagination pagination-centered pagination-mini">
	  <ul>
		<?php echo $this->Paginator->prev(''); ?>
		<?php echo $this->Paginator->numbers(array('first' => 2, 'last' => 2));?>
		<?php echo $this->Paginator->next(''); ?>
	  </ul>
	</div>
</div>
<script>
jQuery(function($) {
	$("#more").click(function(){
		$(".filter-box").toggle('fold');
	});
	$(".date").datepicker();
});
</script>