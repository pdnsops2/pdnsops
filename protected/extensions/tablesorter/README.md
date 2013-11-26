#Tablesorter - YII Extension

jQuery tablesorter extension for YII, for turning a standard grid view into a sortable table without page refreshes. The main aspects of this extension are given below.

- Fields will be listed as similar to gridview with unique bootstrap style.
- Default sort filters available with ajax. 
- We can give options like select box, text box, empty for the filters.
- There are options for relation values as well.

##Requirements

I have tested the extension in <b>YII 1.1.13</b>. It should work fine on <b>YII 1.1 or above...</b>

##Usage

###Step 1
The first step was to download the extension and move it inside your <code>application/protected/extension/</code> folder.

###Step 2
The second step was in your controller created after CRUD. Let's assume you have a controller named PostsController in <code>application/protected/controllers/PostsController.php</code>. Go to actionAdmin() method inside the controller, the default method should look like this below. 

~~~
public function actionAdmin()
{
	$model=new Posts('search');
	$model->unsetAttributes();  // clear any default values
	if(isset($_GET['Posts']))
	$model->attributes=$_GET['Posts'];

	$this->render('admin',array(
		'model'=>$model,
	));
}
~~~

Modify it as like here.
~~~
public function actionAdmin()
{
	$records=Posts::model()->findAll();
	$this->render('admin',array(
		'records'=>$records,
	)); 
}
~~~

###Step 3
The final step was in your view, go to <code>application/protected/views/posts/admin.php</code>. Let's assume we have fields <i>title, description, content and user_id</i> fields. The default grid view should like this.

~~~
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'posts-grid',
	'dataProvider'=>$model->search(),
	//'cssFile' => $grid_css,
	//'filter'=>$model,
	'columns'=>array(
		'id',
		'title',
		'description',
		'content',
		'user_id',
		array(
			'class'=>'CButtonColumn',
		),
	),
));
~~~

Modify it with our table sorter extension below.


~~~
$this->widget('application.extensions.tablesorter.Sorter', array(
	'data'=>$records,
	'columns'=>array(
		'id',
		'title',
		'description',
		'content', 
		'users.username', // Relation value given in model
	)
));
~~~

##Optional
We can set the filter as <b>text box, select box, empty</b>. We need to pass another parameter in the extension widget.


~~~
$this->widget('application.extensions.tablesorter.Sorter', array(
	'data'=>$records,
	'columns'=>array(
		'id',
		'title',
		'description',
		'content',
		'users.username',
	),
	'filters'=>array(
		'',
		'',
		'',
		'filter-false', // won't filter this
		'filter-select', // filter as select box
	),
	)); 
~~~
 

##Resources

Tablesorter is a widely used jquery plugin in many websites. It has various skins and customization options. I will upgrade the functionality in this extension.

 * Project [page](https://github.com/innovativenachi/tablesorter/ "Github")
 * Extension usage demo
 * Mottie modified [tablesorter](http://mottie.github.io/tablesorter/docs/example-widget-bootstrap-theme.html "demo")
 * Table sorter official [page](http://tablesorter.com/docs/ "Tablesorter")
