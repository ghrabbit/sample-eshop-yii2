<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

use yii\data\Pagination;

class Product extends ActiveRecord
{
	/*
	public integer $id;
	public string $title;
	public string $description;
	public string $img_file;
	public double $price;
	public integer $on_special;
	*/

	/**
	 * @return string the associated database table name
	 */
	static public function tableName()
	{
		return 'products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, price', 'required'),
			array('on_special', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('title', 'length', 'max'=>32),
			//array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, description, price, on_special', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'categories'=>array(self::MANY_MANY, 'Category',	'products_categories(product_id, category_id)'),
			'categoriesCount'=>array(self::STAT, 'Category',	'products_categories(product_id, category_id)'),
		);
	}
	/**
	 * @return true if relational categoriesCount > 0.
	 */
	
	public function hasCategories()
	{
		return $this->categoriesCount > 0;
	}
		

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'title' => Yii::t('app','Title'),
			'description' => Yii::t('app','Description'),
			'img_file' => Yii::t('app','Image'),
			'price' => Yii::t('app','Price'),
			'on_special' => Yii::t('app','On Special'),
			'categories' => Yii::t('app','Categories'),
			'productes' => Yii::t('app','Productes'),

			'productDetails' => Yii::t('app','Product Details'),
			'addToCart' => Yii::t('app','Add to Cart'),
			'image' => Yii::t('app','Image'),
			/**
			'total' => Yii::t('app','Total'),
			'qty' => Yii::t('app','Qty'),
			*/
		);
	}

/* get a list of products that are on special 
 * 
 * */
	public function get_on_specials() {
		$products = Product::find()
			->where(['on_special' => 1])
			->orderBy('id')
			->all();

		return 	$products;
	}
	
	public function get_on_specials_page($pageNo = 1, $pageSize = 0) {
		$query = self::find()->where(['on_special' => 1]);
		// get the total number of products (but do not fetch the product data yet)
		$count = $query->count();

		// create a pagination object with the total count
		$pagination = new Pagination(['totalCount' => $count, 'page'=>$pageNo - 1, 'pageSize'=>$pageSize]);

		// limit the query using the pagination and retrieve the products
		$products = $query->offset($pagination->offset)
			->limit($pagination->limit)
			->all();
	
		return 	$products;
	}
	
	public function get_on_specials_total_count() {
		$query = self::find()->where(['on_special' => 1]);
		// get the total number of products (but do not fetch the product data yet)
		return $query->count();
	}
	
	/**
	 * This is invoked after the record is deleted.
	 */
	public function afterDelete()
	{
		parent::afterDelete();
		$this->removeRelations();
	}
	
	public function removeRelations()//$id, $parent_id)
	{
		$connection=Yii::$app->db;
		$command=$connection->createCommand();
		// delete this product from the products_categories table
		$command->delete('products_categories','product_id =:id', 
			array('id'=>$this->id));
	}
	
	function saveForm(array $frm)
	{
		$connection=Yii::$app->db; 
		$transaction=$connection->beginTransaction();
		try
		{
			$this->save();
			//Yii::log("WAS SAVED product <'$this->id'",'warning');
			// delete all the categories this product was associated with 
			$sql = "DELETE FROM products_categories
				WHERE product_id = '$this->id'";
			$command=$connection->createCommand($sql);
			$command->execute();

			// add associations for all the categories this product belongs to, if
			// no categories were selected, we will make it belong to the top
			// category 
			if (!isset($frm["categories"]) || (count($frm["categories"]) == 0)) 
			{
				$frm["categories"][] = 0;
			}

			$frm["categories"] = array_values($frm["categories"]);

			$sql = "INSERT INTO products_categories ( 
					product_id,category_id) VALUES ('$this->id', ':cat_id' )";
					///Yii::log("WAS SAVED <'$this->id':'{$frm["categories"][$i]}'>",'warning');
			$command=$connection->createCommand($sql);
			for ($i = 0; $i < count($frm["categories"]); $i++) {
					$command->bindParam('cat_id', $frm["categories"][$i]);
					$command->execute();
			}
				
			$transaction->commit();
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			$transaction->rollBack();
		}	
		
	}
	
	public function fprice()
	{
		return '<i class="fa fa-rub"></i> '. $this->price;
	}
	
}
