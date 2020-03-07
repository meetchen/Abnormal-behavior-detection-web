<?php


namespace app\mini\controller;


use think\Db;
use think\Exception;

class Equipment
{
    /**
     * @return int|\think\response\Json 返回设备信息
     *  设备不存在返回1
     */
    public function getEquipmentStatus(){
        $equipmentId = input("equipmentId");
        try{
            $result = Db::table('equipment')->where('equipment_id',$equipmentId)->select();
            if ($result == null)
                return 1;
            return json($result);
        }catch (Exception $exception){
            return 1;
        }
    }

    /**
     *
     * 设备注册
     * @return int|string
     *  返回注册成功后的设备号
     */
    public function register()
    {
        $equipment_name = input('equipmentName');
        $latitude = rand(3111, 3500) / 100.0 + rand(0, 10000) * 0.0001;
        $longitude = rand(10577, 11500) / 100.0 + rand(0, 10000) * 0.0001;
        $user_name = input('userName');
        if($equipment_name==null ||$user_name==null){
            return -1;
        }
        $user_id  = Db::table('user')->where('user_user',$user_name)->value('user_id');
        $data = [
            'equipment_name'=>$equipment_name,
            'equipment_location_x'=>$latitude,
            'equipment_location_y'=>$longitude,
            'equipment_user_id'=>$user_id,
            'equipment_status'=>1
        ];
        $equipment_id  = Db::table('equipment')->insertGetId($data);
        Db::name('equipment_user')->insert(['equipment_id'=>$equipment_id,'user_id'=>$user_id]);
        return $equipment_id;
    }

    /**
     * 获取设备运行状态
     * @return int -1 设备号不存在  0 设备正常 1设备故障
     */
    public function getStatus()
    {
        $equipment_id = input("equipmentId");
        if ($equipment_id == null) {
            return -1;
        }
        $db = \think\db::table('equipment')->where('equipment_id', $equipment_id)->value('equipment_status');
        if ($db == null && $db != 0)
            return -1;
        return $db;
    }

    /**
     * 获取设备的经纬度
     * @return int|\think\response\Json -1表示错误，设备不存在
     */
    public function getCoordinate()
    {
        $equipment_id = input("equipmentId");
        if ($equipment_id == null) {
            return -1;
        }
        $result = Db::query('select equipment_location_x,equipment_location_y from equipment 
                                    where equipment_id = ?', [$equipment_id]);
//        $latitude = rand(3111,3500)/100.0+rand(0,10000)*0.0001;
//        $longitude = rand(10577,11500)/100.0+rand(0,10000)*0.0001;
//        $coordinate = ['x'=>$latitude,'y'=>$longitude];
        if ($result == null)
            return -1;
        return json($result[0]);
    }


}