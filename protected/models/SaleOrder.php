<?php

/**
 * This is the model class for table "sale_order".
 *
 * The followings are the available columns in table 'sale_order':
 * @property integer $id
 * @property string $sale_time
 * @property integer $client_id
 * @property integer $desk_id
 * @property integer $zone_id
 * @property integer $group_id
 * @property integer $employee_id
 * @property integer $location_id
 * @property double $sub_total
 * @property string $payment_type
 * @property string $status
 * @property string $remark
 * @property string $discount_amount
 * @property string $discount_type
 * @property integer $giftcard_id
 * @property integer $empty_flag
 */
class SaleOrder extends CActiveRecord
{
    private $active_status = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sale_order';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sale_time', 'required'),
            array(
                'client_id, desk_id, zone_id, group_id, employee_id, location_id, giftcard_id, empty_flag',
                'numerical',
                'integerOnly' => true
            ),
            array('sub_total', 'numerical'),
            array('payment_type', 'length', 'max' => 255),
            array('status', 'length', 'max' => 20),
            array('discount_amount', 'length', 'max' => 15),
            array('discount_type', 'length', 'max' => 2),
            array('remark', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, sale_time, client_id, desk_id, zone_id, group_id, employee_id, location_id, sub_total, payment_type, status, remark, discount_amount, discount_type, giftcard_id, empty_flag',
                'safe',
                'on' => 'search'
            ),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'sale_time' => 'Sale Time',
            'client_id' => 'Client',
            'desk_id' => 'Desk',
            'zone_id' => 'Zone',
            'group_id' => 'Group',
            'employee_id' => 'Employee',
            'location_id' => 'Location',
            'sub_total' => 'Sub Total',
            'payment_type' => 'Payment Type',
            'status' => 'Status',
            'remark' => 'Remark',
            'discount_amount' => 'Discount Amount',
            'discount_type' => 'Discount Type',
            'giftcard_id' => 'Giftcard',
            'empty_flag' => 'Empty Flag',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SaleOrder the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    //public function getOrderCart($desk_id, $group_id, $location_id)
    //public function getOrderCart($sale_id, $location_id)
    public function getOrderCart($desk_id,$group_id,$location_id)
    {

        $sql = "select * 
              from (
                    SELECT 0 child_id,sale_id,item_number,item_id,line,`name`,quantity,price,discount_amount discount,cart_order,cart_status,
                    total,client_id,desk_id,zone_id,employee_id,qty_in_stock,topping,item_parent_id,category_id,item_id item_sort,1 Sorting
                    FROM v_order_cart
                    WHERE desk_id=:desk_id
                    AND group_id=:group_id
                    AND location_id=:location_id
                    AND status=:status
                    AND deleted_at is null
                    union all
                    SELECT child_id,sale_id,item_number,item_id,line,`name`,quantity,price,discount,cart_order,cart_status,
                    price AS total,client_id,desk_id,zone_id,employee_id,
                    quantity qty_in_stock,topping,item_parent_id,category_id,item_parent_id item_sort,2 Sorting
                    FROM v_topping_add
                    WHERE desk_id=:desk_id2
                    AND group_id=:group_id2
                    AND location_id=:location_id2
                    AND status=:status2
                    AND deleted_at is null
                )as l1
                ORDER BY sale_id,item_sort,line,Sorting";

        return Yii::app()->db->createCommand($sql)->queryAll(true, array(
                ':desk_id' => $desk_id,
                ':group_id' => $group_id,
                ':location_id' => $location_id,
                ':status' => Yii::app()->params['num_one'],
                ':desk_id2' => $desk_id,
                ':group_id2' => $group_id,
                ':location_id2' => $location_id,
                ':status2' => Yii::app()->params['num_one']
            )
        );
    }


    public function getOrderCart4Payment($desk_id,$group_id,$location_id)
    {

        $sql = "select * 
              from (
                    SELECT 0 child_id,sale_id,item_number,item_id,line,`name`,quantity,price,discount_amount discount,
                    total,client_id,desk_id,zone_id,employee_id,qty_in_stock,topping,item_parent_id,category_id,item_id item_sort,1 Sorting
                    FROM v_order_cart
                    WHERE desk_id=:desk_id
                    AND group_id=:group_id
                    AND location_id=:location_id
                    AND status in (:status,:status_confirmed)
                    AND deleted_at is null
                    union all
                    SELECT child_id,sale_id,item_number,item_id,line,`name`,quantity,price,discount,
                    price AS total,client_id,desk_id,zone_id,employee_id,
                    quantity qty_in_stock,topping,item_parent_id,category_id,item_parent_id item_sort,2 Sorting
                    FROM v_topping_add
                    WHERE desk_id=:desk_id2
                    AND group_id=:group_id2
                    AND location_id=:location_id2
                    AND status in (:status2,:status_confirmed2)
                    AND deleted_at is null
                )as l1
                ORDER BY sale_id,item_sort,line,Sorting";

        return Yii::app()->db->createCommand($sql)->queryAll(true, array(
                ':desk_id' => $desk_id,
                ':group_id' => $group_id,
                ':location_id' => $location_id,
                ':status' => Yii::app()->params['num_one'],
                'status_confirmed' => Yii::app()->params['sale_confirmed'],
                ':desk_id2' => $desk_id,
                ':group_id2' => $group_id,
                ':location_id2' => $location_id,
                ':status2' => Yii::app()->params['num_one'],
                'status_confirmed2' => Yii::app()->params['sale_confirmed'],
            )
        );
    }

    /*public function getNumberCart($desk_id,$group_id,$location_id)
    {
        $sql="select COUNT(distinct sale_id) nRec 
            FROM v_order_cart
            WHERE desk_id=:desk_id
            AND group_id=:group_id
            AND location_id=:location_id
            AND status=:status
            AND deleted_at is null";

        return Yii::app()->db->createCommand($sql)
            ->bindValue(':desk_id' , $desk_id)
            ->bindValue(':group_id' , $group_id)
            ->bindValue(':location_id' , $location_id)
            ->bindValue(':status' , Yii::app()->params['num_one'])
            ->queryScalar();
    }*/


    public function getDetailCartNumber($desk_id,$group_id,$location_id)
    {
        $sql="select distinct cart_order sale_id
            FROM v_order_cart
            WHERE desk_id=:desk_id
            AND group_id=:group_id
            AND location_id=:location_id
            AND (status =:status or temp_status=:temp_status)
            AND deleted_at is null
            order by cart_order";

        return Yii::app()->db->createCommand($sql)
            ->bindValue(':desk_id' , $desk_id)
            ->bindValue(':group_id' , $group_id)
            ->bindValue(':location_id' , $location_id)
            ->bindValue(':status' , Yii::app()->params['num_one'])
            ->bindValue(':temp_status' , Yii::app()->params['temp_edit_status'])
            ->queryAll();
    }

    public function getCartOrderId($sale_id,$cart_id)
    {

    }

    public function getOrderCartGrid($desk_id,$group_id,$location_id,$item_id)
    {

        $sql="select * 
              from (
                    SELECT 0 child_id,sale_id,item_number,item_id,line,`name`,quantity,price,discount_amount discount,
                    total,client_id,desk_id,zone_id,employee_id,qty_in_stock,topping,item_parent_id,category_id,item_id item_sort,1 Sorting
                    FROM v_order_cart
                    WHERE desk_id=:desk_id
                    AND group_id=:group_id
                    AND location_id=:location_id
                    AND status =:status
                    AND deleted_at is null
                    union all
                    SELECT child_id,sale_id,item_number,item_id,line,`name`,quantity,price,discount,
                    price AS total,client_id,desk_id,zone_id,employee_id,
                    quantity qty_in_stock,topping,item_parent_id,category_id,item_parent_id item_sort,2 Sorting
                    FROM v_topping_add
                    WHERE desk_id=:desk_id2
                    AND group_id=:group_id2
                    AND location_id=:location_id2
                    AND status = :status2
                    AND deleted_at is null
                )as l1
                where (item_id=:item_id or item_parent_id=:item_id_2)
                ORDER BY sale_id,item_sort,line,Sorting";

        $rawData = Yii::app()->db->createCommand($sql)->queryAll(true, array(
            ':desk_id' => $desk_id,
            ':group_id' => $group_id,
            ':location_id' => $location_id,
            ':status' => Yii::app()->params['num_one'],
            //':temp_status' => Yii::app()->params['temp_edit_status'],
            ':desk_id2' => $desk_id,
            ':group_id2' => $group_id,
            ':location_id2' => $location_id,
            ':status2' => Yii::app()->params['num_one'],
            //':temp_status2' => Yii::app()->params['temp_edit_status'],
            ':item_id' => $item_id,
            ':item_id_2' => $item_id
            )
            );

        return $rawData;
    }


    public function getOrderCartIndex($desk_id,$group_id,$location_id)
    {

        $sql = "select * 
              from (
                    SELECT 0 child_id,sale_id,item_number,item_id,line,`name`,quantity,price,discount_amount discount,cart_order,cart_status,
                    total,client_id,desk_id,zone_id,employee_id,qty_in_stock,topping,item_parent_id,category_id,item_id item_sort,1 Sorting
                    FROM v_order_cart
                    WHERE desk_id=:desk_id
                    AND group_id=:group_id
                    AND location_id=:location_id
                    AND status=:status
                    AND cart_status=:cart_status
                    AND deleted_at is null
                    union all
                    SELECT child_id,sale_id,item_number,item_id,line,`name`,quantity,price,discount,cart_order,cart_status,
                    price AS total,client_id,desk_id,zone_id,employee_id,
                    quantity qty_in_stock,topping,item_parent_id,category_id,item_parent_id item_sort,2 Sorting
                    FROM v_topping_add
                    WHERE desk_id=:desk_id2
                    AND group_id=:group_id2
                    AND location_id=:location_id2
                    AND status=:status2
                    AND cart_status=:cart_status2
                    AND deleted_at is null
                )as l1
                ORDER BY sale_id,item_sort,line,Sorting";

        return Yii::app()->db->createCommand($sql)->queryAll(true, array(
                ':desk_id' => $desk_id,
                ':group_id' => $group_id,
                ':location_id' => $location_id,
                ':status' => Yii::app()->params['num_one'],
                ':cart_status' => Yii::app()->params['cart_new_order_status'],
                ':desk_id2' => $desk_id,
                ':group_id2' => $group_id,
                ':location_id2' => $location_id,
                ':status2' => Yii::app()->params['num_one'],
                ':cart_status2' => Yii::app()->params['cart_new_order_status'],
            )
        );
    }


    public function getOrderCartEdit($desk_id,$group_id,$location_id,$cartnum='')
    {
        $sql = "select * 
              from (
                    SELECT 0 child_id,sale_id,item_number,item_id,line,`name`,quantity,price,discount_amount discount,cart_order,cart_status,
                    total,client_id,desk_id,zone_id,employee_id,qty_in_stock,topping,item_parent_id,category_id,item_id item_sort,1 Sorting
                    FROM v_order_cart
                    WHERE desk_id=:desk_id
                    AND group_id=:group_id
                    AND location_id=:location_id
                    AND (status =:status or temp_status=:temp_status)
                    AND cart_status=:cart_status
                    AND deleted_at is null
                    union all
                    SELECT child_id,sale_id,item_number,item_id,line,`name`,quantity,price,discount,cart_order,cart_status,
                    price AS total,client_id,desk_id,zone_id,employee_id,
                    quantity qty_in_stock,topping,item_parent_id,category_id,item_parent_id item_sort,2 Sorting
                    FROM v_topping_add
                    WHERE desk_id=:desk_id2
                    AND group_id=:group_id2
                    AND location_id=:location_id2
                    AND (status =:status2 or temp_status=:temp_status2)
                    AND cart_status=:cart_status2
                    AND deleted_at is null
                )as l1
                where cart_order=:cartnum
                ORDER BY sale_id,item_sort,line,Sorting";

        return Yii::app()->db->createCommand($sql)->queryAll(true, array(
                ':desk_id' => $desk_id,
                ':group_id' => $group_id,
                ':location_id' => $location_id,
                ':status' => Yii::app()->params['num_one'],
                ':temp_status' => Yii::app()->params['temp_edit_status'],
                ':cart_status' => Yii::app()->params['cart_confirmed_status'],
                ':desk_id2' => $desk_id,
                ':group_id2' => $group_id,
                ':location_id2' => $location_id,
                ':status2' => Yii::app()->params['num_one'],
                ':temp_status2' => Yii::app()->params['temp_edit_status'],
                ':cart_status2' => Yii::app()->params['cart_confirmed_status'],
                ':cartnum' => $cartnum
            )
        );
    }


    public function getOrderCartCashier($desk_id,$group_id,$location_id)
    {
        $sql="SELECT l2.item_sort item_id,0 price,0 discount,null modified_date,
                (SELECT item_number FROM item it WHERE it.id=l2.item_sort LIMIT 1) item_number,
                (SELECT `name` FROM item it WHERE it.id=l2.item_sort LIMIT 1) `name`,
                ItemQry quantity,ToppingQty topping,total,
                null client_id,null desk_id,null zone_id,null employee_id,0 qty_in_stock
                FROM(
                    SELECT item_sort,
                    SUM(total) total,
                    COUNT(CASE WHEN item_type='Parent' THEN 1 ELSE NULL END) ItemQry,
                    COUNT(CASE WHEN item_type='Child' THEN 1 ELSE NULL END) ToppingQty
                    FROM(
                        SELECT 'Parent' item_type,sale_id,item_number,item_id,line,`name`,quantity,price,discount_amount,
                        total,client_id,desk_id,zone_id,employee_id,qty_in_stock,topping,item_parent_id,category_id,item_id item_sort,1 Sorting
                        FROM v_order_cart
                        WHERE desk_id=:desk_id
                        AND group_id=:group_id
                        AND location_id=:location_id
                        AND (status in (:status,3) or temp_status=:temp_status)
                        AND deleted_at IS NULL
                        UNION ALL
                        SELECT 'Child' item_type,sale_id,item_number,item_id,line,`name`,quantity,price,discount,
                        price AS total,client_id,desk_id,zone_id,employee_id,
                        quantity qty_in_stock,topping,item_parent_id,category_id,item_parent_id item_sort,2 Sorting
                        FROM v_topping_add
                        WHERE desk_id=:desk_id2
                        AND group_id=:group_id2
                        AND location_id=:location_id2
                        AND (status in (:status2,3) or temp_status=:temp_status2)
                        AND deleted_at IS NULL 
                    )AS l1
                    GROUP BY item_sort
                )AS l2";

        return Yii::app()->db->createCommand($sql)->queryAll(true, array(
                ':desk_id' => $desk_id,
                ':group_id' => $group_id,
                ':location_id' => $location_id,
                ':status' => Yii::app()->params['num_one'],
                ':temp_status' => Yii::app()->params['temp_edit_status'],
                ':desk_id2' => $desk_id,
                ':group_id2' => $group_id,
                ':location_id2' => $location_id,
                ':status2' => Yii::app()->params['num_one'],
                ':temp_status2' => Yii::app()->params['temp_edit_status'],
            )
        );
    }

    public function getIndividualCard($desk_id,$group_id,$location_id,$cartnum='')
    {
        //echo $cartnum;
        $sql="SELECT l2.item_sort item_id,0 price,0 discount,null modified_date,
                (SELECT item_number FROM item it WHERE it.id=l2.item_sort LIMIT 1) item_number,
                (SELECT `name` FROM item it WHERE it.id=l2.item_sort LIMIT 1) `name`,
                ItemQry quantity,ToppingQty topping,total,
                null client_id,null desk_id,null zone_id,null employee_id,0 qty_in_stock
                FROM(
                    SELECT item_sort,
                    SUM(total) total,
                    COUNT(CASE WHEN item_type='Parent' THEN 1 ELSE NULL END) ItemQry,
                    COUNT(CASE WHEN item_type='Child' THEN 1 ELSE NULL END) ToppingQty
                    FROM(
                        SELECT 'Parent' item_type,sale_id,item_number,item_id,line,`name`,quantity,price,discount_amount,cart_order,
                        total,client_id,desk_id,zone_id,employee_id,qty_in_stock,topping,item_parent_id,category_id,item_id item_sort,1 Sorting
                        FROM v_order_cart
                        WHERE desk_id=:desk_id
                        AND group_id=:group_id
                        AND location_id=:location_id
                        AND (status =:status or temp_status=:temp_status)
                        AND deleted_at IS NULL
                        UNION ALL
                        SELECT 'Child' item_type,sale_id,item_number,item_id,line,`name`,quantity,price,discount,cart_order,
                        price AS total,client_id,desk_id,zone_id,employee_id,
                        quantity qty_in_stock,topping,item_parent_id,category_id,item_parent_id item_sort,2 Sorting
                        FROM v_topping_add
                        WHERE desk_id=:desk_id2
                        AND group_id=:group_id2
                        AND location_id=:location_id2
                        AND (status =:status or temp_status=:temp_status2)
                        AND deleted_at IS NULL 
                    )AS l1
                    where cart_order=:cartnum
                    GROUP BY item_sort
                )AS l2";

        return Yii::app()->db->createCommand($sql)->queryAll(true, array(
                ':desk_id' => $desk_id,
                ':group_id' => $group_id,
                ':location_id' => $location_id,
                ':status' => Yii::app()->params['num_one'],
                ':temp_status' => Yii::app()->params['temp_edit_status'],
                ':desk_id2' => $desk_id,
                ':group_id2' => $group_id,
                ':location_id2' => $location_id,
                ':status2' => Yii::app()->params['num_one'],
                ':temp_status2' => Yii::app()->params['temp_edit_status'],
                ':cartnum' => $cartnum
            )
        );
    }


    public function getOrderToKitchen($sale_id, $location_id, $category_id)
    {
        $sql = "SELECT t1.item_number,t1.item_id,t1.`name`,(t1.quantity-IFNULL(t2.`quantity`,0)) quantity,
                t1.price,t1.discount_amount discount,t1.total,t1.client_id,t1.desk_id,t1.zone_id,t1.employee_id,t1.qty_in_stock,t1.topping,t1.item_parent_id
                FROM v_order_cart t1 LEFT JOIN
                        (SELECT t2.sale_id,t2.item_id,t2.item_parent_id ,t2.quantity
                         FROM sale_order_item_print t2 , item t3
                         WHERE t3.id=t2.item_id
                         AND t3.category_id=:category_id
                        ) t2
                    ON t2.sale_id=t1.`sale_id`
                    AND t2.item_id=t1.item_id
                    AND t2.item_parent_id=t1.item_parent_id
                WHERE t1.sale_id=:sale_id and t1.location_id=:location_id
                AND t1.status=:status
                AND (t1.quantity-IFNULL(t2.quantity,0)) > 0
                AND t1.category_id=:category_id
                ORDER BY t1.path,t1.modified_date";

            return Yii::app()->db->createCommand($sql)->queryAll(true, array(
                ':sale_id' => $sale_id,
                ':location_id' => $location_id,
                ':category_id' => $category_id,
                ':status' => Yii::app()->params['num_one']
            )
        );
    }

    public function getOrderCartTopping($desk_id, $group_id, $location_id)
    {
        $sql = "SELECT item_id,`name`,quantity,price,discount_amount discount,total,
                client_id,desk_id,zone_id,employee_id,qty_in_stock
                FROM v_order_cart
                WHERE desk_id=:desk_id AND group_id=:group_id and location=:location_id
                AND status=:status
                AND topping=1
                ORDER BY modified_date desc";


        return Yii::app()->db->createCommand($sql)->queryAll(true, array(
            ':desk_id' => (int)$desk_id,
            ':group_id' => $group_id,
            ':location_id' => $location_id,
            ':status' => Yii::app()->params['num_one']
        ));
    }

    //public function getAllTotal($desk_id, $group_id, $location_id)
    public function getAllTotal($desk_id,$group_id, $location_id)
    {
        $quantity = 0;
        $sub_total = 0;
        $total = 0;
        $discount_amount = 0;

        /*$sql="SELECT sale_id,sum(quantity) quantity,
                    SUM(price*quantity) sub_total,
                    SUM(price*quantity) - (SUM(price*quantity)*IFNULL(so.discount_amount,0)/100) total,
                    SUM(price*quantity)*IFNULL(so.discount_amount,0)/100 discount_amount
                FROM (
                SELECT sale_id,quantity,price,desk_id,group_id,location_id,status,deleted_at
                    FROM v_order_cart
                    UNION ALL
                    SELECT sale_id,quantity,price,desk_id,group_id,location_id,status,deleted_at
                    FROM v_topping_add                
                )as oc JOIN sale_order so
                            ON so.id=oc.sale_id 
                            and so.desk_id=oc.desk_id
                            and so.group_id=oc.group_id
                            and so.location_id=oc.location_id
                WHERE oc.desk_id=:desk_id 
                AND oc.group_id=:group_id
                AND oc.location_id=:location_id
                AND (oc.status in (:status,:sale_confirmed) or temp_status=:temp_edit_status) 
                AND ISNULL(oc.deleted_at)
                GROUP BY sale_id";*/

        $sql="select quantity,
                    sub_total,
                    sub_total - (sub_total*IFNULL(discount_amount,0)/100) total,
                    sub_total*IFNULL(discount_amount,0)/100 discount_amount
            from (select sum(quantity) quantity,SUM(price*quantity) sub_total,max(discount_amount) discount_amount
                FROM (
                    SELECT sale_id,quantity,price,desk_id,group_id,location_id,status,deleted_at,discount_amount,temp_status,cart_status
                        FROM v_order_cart
                        UNION ALL
                        SELECT sale_id,quantity,price,desk_id,group_id,location_id,status,deleted_at,discount,temp_status,cart_status
                        FROM v_topping_add                
                    )as oc
                    WHERE oc.desk_id=:desk_id 
                    AND oc.group_id=:group_id
                    AND oc.location_id=:location_id
                    AND ((oc.status = :status and cart_status=:cart_confirmed) or temp_status=:temp_edit_status) 
                    AND ISNULL(oc.deleted_at)                
            )as l2";


        $result = Yii::app()->db->createCommand($sql)->queryAll(true, array(
            ':desk_id' => $desk_id,
            ':group_id' => $group_id,
            ':location_id' => $location_id,
            ':status' => Yii::app()->params['num_one'],
            ':cart_confirmed' => Yii::app()->params['cart_confirmed_status'],
            ':temp_edit_status' => Yii::app()->params['temp_edit_status']

        ));

        if ($result) {
            foreach ($result as $record) {
                $quantity = $record['quantity'];
                $sub_total = $record['sub_total'];
                $total = $record['total'];
                $discount_amount = $record['discount_amount'];
            }
        }

        return array($quantity, $sub_total, $total, $discount_amount);
    }


    public function getEditTotal($desk_id,$group_id,$location_id,$cart_order)
    {
        //echo $cart_order;

        $quantity = 0;
        $sub_total = 0;
        $total = 0;
        $discount_amount = 0;


        $sql="select quantity,
                    sub_total,
                    sub_total - (sub_total*IFNULL(discount_amount,0)/100) total,
                    sub_total*IFNULL(discount_amount,0)/100 discount_amount
            from (select sum(quantity) quantity,SUM(price*quantity) sub_total,max(discount_amount) discount_amount
                FROM (
                    SELECT sale_id,quantity,price,desk_id,group_id,location_id,status,deleted_at,discount_amount,temp_status,cart_status,cart_order
                        FROM v_order_cart
                        UNION ALL
                        SELECT sale_id,quantity,price,desk_id,group_id,location_id,status,deleted_at,discount,temp_status,cart_status,cart_order
                        FROM v_topping_add                
                    )as oc
                    WHERE oc.desk_id=:desk_id 
                    AND oc.group_id=:group_id
                    AND oc.location_id=:location_id
                    AND ((oc.status = :status and cart_status=:cart_confirmed) or temp_status=:temp_edit_status) 
                    and cart_order=:cart_order
                    AND ISNULL(oc.deleted_at)                
            )as l2";


        $result = Yii::app()->db->createCommand($sql)->queryAll(true, array(
            ':desk_id' => $desk_id,
            ':group_id' => $group_id,
            ':location_id' => $location_id,
            ':status' => Yii::app()->params['num_one'],
            ':cart_confirmed' => Yii::app()->params['cart_confirmed_status'],
            ':temp_edit_status' => Yii::app()->params['temp_edit_status'],
            ':cart_order' => $cart_order,
        ));

        if ($result) {
            foreach ($result as $record) {
                $quantity = $record['quantity'];
                $sub_total = $record['sub_total'];
                $total = $record['total'];
                $discount_amount = $record['discount_amount'];
            }
        }

        return array($quantity, $sub_total, $total, $discount_amount);
    }


    public function getIndexTotal($desk_id,$group_id,$location_id)
    {
        //echo $cart_order;

        $quantity = 0;
        $sub_total = 0;
        $total = 0;
        $discount_amount = 0;


        $sql="select quantity,
                    sub_total,
                    sub_total - (sub_total*IFNULL(discount_amount,0)/100) total,
                    sub_total*IFNULL(discount_amount,0)/100 discount_amount
            from (select sum(quantity) quantity,SUM(price*quantity) sub_total,max(discount_amount) discount_amount
                FROM (
                    SELECT sale_id,quantity,price,desk_id,group_id,location_id,status,deleted_at,discount_amount,temp_status,cart_status,cart_order
                        FROM v_order_cart
                        UNION ALL
                        SELECT sale_id,quantity,price,desk_id,group_id,location_id,status,deleted_at,discount,temp_status,cart_status,cart_order
                        FROM v_topping_add                
                    )as oc
                    WHERE oc.desk_id=:desk_id 
                    AND oc.group_id=:group_id
                    AND oc.location_id=:location_id
                    AND oc.status = :status
                    and cart_status=:cart_status
                    AND ISNULL(oc.deleted_at)                
            )as l2";

        $result = Yii::app()->db->createCommand($sql)->queryAll(true, array(
            ':desk_id' => $desk_id,
            ':group_id' => $group_id,
            ':location_id' => $location_id,
            ':status' => Yii::app()->params['num_one'],
            ':cart_status' => Yii::app()->params['cart_new_order_status'],
        ));

        if ($result) {
            foreach ($result as $record) {
                $quantity = $record['quantity'];
                $sub_total = $record['sub_total'];
                $total = $record['total'];
                $discount_amount = $record['discount_amount'];
            }
        }

        return array($quantity, $sub_total, $total, $discount_amount);
    }


    public function orderAdd($item_id, $table_id, $group_id, $client_id, $employee_id, $quantity, $price_tier_id, $item_parent_id, $location_id,$cart_id,$method)
    {
        $sql = "SELECT func_order_add(:item_id,:item_number,:desk_id,:group_id,:client_id,:employee_id,:quantity,:price_tier_id,:item_parent_id,:location_id,:cart_id,:method) item_id";

        $result = Yii::app()->db->createCommand($sql)->queryAll(true,
            array(
                ':item_id' => $item_id,
                ':item_number' => $item_id,
                ':desk_id' => $table_id,
                ':group_id' => $group_id,
                ':client_id' => $client_id,
                ':employee_id' => $employee_id,
                ':quantity' => $quantity,
                ':price_tier_id' => $price_tier_id,
                ':item_parent_id' => $item_parent_id,
                ':location_id' => $location_id,
                ':cart_id' => $cart_id,
                ':method' => $method
            )
        );


        foreach ($result as $record) {
            $id = $record['item_id'];
        }

        return $id;
    }

    public function orderToppingAdd($item_id, $table_id, $group_id, $client_id, $employee_id, $quantity, $price_tier_id, $item_parent_id, $location_id,$line)
    {

        $sql = "SELECT func_topping_add(:item_id,:item_number,:desk_id,:group_id,:client_id,:employee_id,:quantity,:price_tier_id,:item_parent_id,:location_id,:line) item_id";

        $result = Yii::app()->db->createCommand($sql)->queryAll(true,
            array(
                ':item_id' => $item_id,
                ':item_number' => $item_id,
                ':desk_id' => $table_id,
                ':group_id' => $group_id,
                ':client_id' => $client_id,
                ':employee_id' => $employee_id,
                ':quantity' => $quantity,
                ':price_tier_id' => $price_tier_id,
                ':item_parent_id' => $item_parent_id,
                ':location_id' => $location_id,
                ':line' => $line
            )
        );

        foreach ($result as $record) {
            $id = $record['item_id'];
        }

        return $id;
    }

    public function orderEdit($sale_id, $item_id, $quantity, $price, $discount, $item_parent_id)
    {
        //$sql = "CALL proc_edit_menu_order(:desk_id,:group_id,:item_id,:quantity,:price,:discount,:item_parent_id,:location_id)";
        $sql = "SELECT func_order_edit(:sale_id,:item_id,:quantity,:price,:discount,:item_parent_id,:location_id,:employee_id) result_id";

        $result = Yii::app()->db->createCommand($sql)->queryAll(true,
            array(
                ':sale_id' => $sale_id,
                ':item_id' => $item_id,
                ':quantity' => $quantity,
                ':price' => $price,
                ':discount' => $discount,
                ':item_parent_id' => $item_parent_id,
                ':location_id' => Common::getCurLocationID(),
                ':employee_id' => Common::getEmployeeID()
            )
        );

        foreach ($result as $record) {
            $result_id = $record['result_id'];
        }

        return $result_id;
    }

    public function orderDel($item_id,$line, $item_parent_id, $table_id, $group_id,$child_id=0)
    {
        //$sql = "CALL proc_del_item_cart(:item_id,:item_parent_id,:desk_id,:group_id,:location_id)";
        $sql = "SELECT func_order_del(:item_id,:line,:item_parent_id,:desk_id,:group_id, :location_id, :employee_id,:child_id) result_id";

        $result = Yii::app()->db->createCommand($sql)->queryAll(true,
            array(
                ':item_id' => $item_id,
                ':line' => $line,
                ':item_parent_id' => $item_parent_id,
                ':desk_id' => $table_id,
                ':group_id' => $group_id,
                ':location_id' => Common::getCurLocationID(),
                ':employee_id' => Common::getEmployeeID(),
                ':child_id' => $child_id
            )
        );

        foreach ($result as $record) {
            $result_id = $record['result_id'];
        }

        return $result_id;
    }

    public function orderSave($desk_id,$group_id,$payment_total)
    {
        $sql="SELECT func_order_save(:desk_id,:group_id,:location_id,:payment_total,:employee_id) sale_id";
        $result = Yii::app()->db->createCommand($sql)->queryAll(true, array(
                ':desk_id' => $desk_id,
                ':group_id' => $group_id,
                ':location_id' => Common::getCurLocationID(),
                ':payment_total' => $payment_total,
                ':employee_id' => Common::getEmployeeID()
            )
        );
        foreach ($result as $record) {
            $sale_id = $record['sale_id'];
        }
        return $sale_id;
    }

    public function cancelOrderMenu($desk_id, $group_id, $location_id)
    {
        $sql = "delete from sale_order where desk_id=:desk_id and group_id=:group_id and location_id=:location_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":desk_id", $desk_id, PDO::PARAM_INT);
        $command->bindParam(":group_id", $group_id, PDO::PARAM_INT);
        $command->bindParam(":location_id", $location_id, PDO::PARAM_INT);
        $command->execute();
    }

    public function changeTable($desk_id, $new_desk_id, $group_id, $location_id, $price_tier_id, $employee_id)
    {
        $sql = "SELECT func_change_table(:desk_id,:new_desk_id,:group_id,:location_id,:price_tier_id,:employee_id) group_id";
        $result = Yii::app()->db->createCommand($sql)->queryAll(true, array(
                ':desk_id' => $desk_id,
                ':new_desk_id' => $new_desk_id,
                ':group_id' => $group_id,
                ':location_id' => $location_id,
                ':price_tier_id' => $price_tier_id,
                ':employee_id' => $employee_id
            )
        );

        foreach ($result as $record) {
            $group_id = $record['group_id'];
        }

        return $group_id;
    }

    public function savePrintedToKitchen($sale_id, $location_id, $category_id,$employee_id)
    {
        $sql = "select func_save_pkitchen(:sale_id,:location_id,:category_id,:employee_id) result_id";
        $result = Yii::app()->db->createCommand($sql)->queryAll(true,
            array(
                ':sale_id' => $sale_id,
                ':location_id' => $location_id,
                ':category_id' => $category_id,
                ':employee_id' => $employee_id
            )
        );

        foreach ($result as $record) {
            $id = $record['result_id'];
        }

        return $id;
    }

    /*
    public function delOrder($desk_id, $group_id, $location_id)
    {
        $sql = "CALL proc_del_sale_order(:desk_id,:group_id,:location_id)";
        Yii::app()->db->createCommand($sql)->queryAll(true,
            array(
                ':desk_id' => $desk_id,
                ':group_id' => $group_id,
                ':location_id' => $location_id
            )
        );
    }
    */

    public function setDisGiftcard($giftcard_id)
    {

        $sql = "SELECT func_giftcard_set(:desk_id,:group_id,:location_id,:giftcard_id,:employee_id) result_id";
        $result = Yii::app()->db->createCommand($sql)->queryAll(true,
            array(
                ':desk_id' => Common::getTableID(),
                ':group_id' => Common::getGroupID(),
                ':location_id' => Common::getCurLocationID(),
                ':giftcard_id' => $giftcard_id,
                ':employee_id' => Common::getEmployeeID(),
            )
        );

        foreach ($result as $record) {
            $result_id = $record['result_id'];
        }

        return $result_id;

    }

    public function clearDisGiftcard()
    {

        $sql = "SELECT func_giftcard_clear(:desk_id,:group_id,:location_id,:employee_id) result_id";
        $result = Yii::app()->db->createCommand($sql)->queryAll(true,
            array(
                ':desk_id' => Common::getTableID(),
                ':group_id' => Common::getGroupID(),
                ':location_id' => Common::getCurLocationID(),
                ':employee_id' => Common::getEmployeeID(),
            )
        );

        foreach ($result as $record) {
            $result_id = $record['result_id'];
        }

        return $result_id;
    }

    public function getDisGiftcard($desk_id, $group_id, $location_id)
    {
        $sql = "SELECT giftcard_id
                FROM sale_order
                WHERE desk_id=:desk_id AND group_id=:group_id
                AND location_id=:location_id
                and status=:status";

        $result = Yii::app()->db->createCommand($sql)->queryAll(true, array(
            ':desk_id' => $desk_id,
            ':group_id' => $group_id,
            ':location_id' => $location_id,
            ':status' => Yii::app()->params['num_one']
        ));

        if ($result) {
            foreach ($result as $record) {
                $giftcard_id = $record['giftcard_id'];
            }
        } else {
            $giftcard_id = 0;
        }

        return $giftcard_id;
    }

    public function countNewOrder()
    {
        $sql = "SELECT COUNT(*) count_order
                FROM sale_order
                WHERE location_id = :location_id
                and sale_time >= CURDATE()
                AND `status`=:status
                AND temp_status <> :str_zero
                AND employee_id <> :employee_id";

        $result = Yii::app()->db->createCommand($sql)->queryAll(true, array(
            ':location_id' => Yii::app()->getsetSession->getLocationId(),
            ':status' => Yii::app()->params['num_one'],
            ':str_zero' => Yii::app()->params['str_zero'],
            ':employee_id' => Yii::app()->session['employeeid']
        ));

        if ($result) {
            foreach ($result as $record) {
                $count_order = $record['count_order'];
            }
        } else {
            $count_order = 0;
        }

        return $count_order;
    }

    public function newOrdering()
    {
        $sql="SELECT so.desk_id,d.`name` desk_name, concat(hour(so.sale_time), ':',minute(so.sale_time)) sale_time
                FROM sale_order so JOIN desk d ON d.id = so.desk_id
                WHERE so.location_id = :location_id
                AND so.sale_time >= CURDATE()
                AND so.`status`=:status
                AND temp_status <> :str_zero
                AND employee_id <> :employee_id
                ORDER BY so.sale_time desc";

        $result = Yii::app()->db->createCommand($sql)->queryAll(true, array(
            ':location_id' => Common::getCurLocationID(),
            ':status' => Yii::app()->params['num_one'],
            ':str_zero' => Yii::app()->params['str_zero'],
            ':employee_id' => Common::getEmployeeID()
        ));

        return $result;
    }

    public function getSaleOrderByDeskId()
    {
        $sale_order = SaleOrder::model()->find('desk_id=:desk_id and group_id=:group_id and location_id=:location_id and status=:status',
            array(
                ':desk_id' => Common::getTableID(),
                ':group_id' => Common::getGroupID(),
                ':location_id' => Common::getCurLocationID(),
                ':status' => Yii::app()->params['num_one']
            ));

        return isset($sale_order) ? $sale_order : null;
    }

    public function updateConfirmedCart($sale_id,$cart_order=0)
    {
        $cart_status_conf=Yii::app()->params['cart_confirmed_status'];

        $sql = "update sale_order_item 
        set cart_status=:cart_status
        where sale_id=:sale_id and cart_order=:cart_order
        and deleted_at is null";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":cart_status", $cart_status_conf, PDO::PARAM_INT);
        $command->bindParam(":sale_id", $sale_id, PDO::PARAM_INT);
        $command->bindParam(":cart_order", $cart_order, PDO::PARAM_INT);
        $command->execute();
    }

    public function getSaleOrderById($sale_id,$location_id)
    {
        $sale_order = SaleOrder::model()->find('id=:sale_id and location_id=:location_id and status=:status',
            array(
                ':sale_id' => $sale_id,
                ':location_id' => $location_id,
                ':status' => Yii::app()->params['num_one']
            ));

        return isset($sale_order) ? $sale_order : null;
    }

    public function updateSaleOrderTempStatus($status,$cart_order=0)
    {
        //$cart_order=$v_cart_order;

        //if($cart_order=='' || $cart_order=0) $cart_order=1;
        $cart_order=($cart_order==''?0:$cart_order);

        $model = $this->getSaleOrderByDeskId();

        if ($model !== null) {
            $model->temp_status = $status;
            //$model->status =Yii::app()->params['sale_confirmed'];
            //$sale_item = $this->getSaleCartOrderId($model->id,$cart_order);
            //print_r($sale_item);
            //die();
            if($model->save())
            {
                $sale_item = $this->updateConfirmedCart($model->id,$cart_order);
                Yii::app()->orderingCart->emptyCartNum(); //if confirmed cart in session need to be cleared

                //$sale_item->cart_status=Yii::app()->params['cart_confirmed_status'];

                //$sale_item->save();
            }
        }
    }

}
