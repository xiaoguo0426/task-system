<?php
// +------------------------------------------------+
// | blog.yhcloud.cc 					 			
// +------------------------------------------------+
// | @Author: Johnny   					  			
// +------------------------------------------------+
// | Date: 2019-03-18 22:12                          
// +------------------------------------------------+
namespace App;

class WxAppFormID
{
    // 推送小程序的form key
    const PREFIX = 'formID::';

    // 最大保存数量
    const MAX_SAVE_SIZE = 30;

    private static function getRedisHandle()
    {
        return Redis::getInstance();
    }

    /**
     * @param $user_id
     * @return string
     */
    public static function getFormKey($siteID, $user_id)
    {
        return self::PREFIX . $siteID . '::' . $user_id;
    }

    /**
     * 保存form_id
     * @param $user_id
     * @param $form_id
     * @return mixed
     */
    public static function saveFormId($siteID, $user_id, $form_id)
    {
        $key = self::getFormKey($siteID, $user_id);

        // 有效期提前0.5天，确保业务平滑过渡
        $expire_time = strtotime('+6 day +12 hours');
        self::getRedisHandle()->zAdd($key, $expire_time, $form_id);

        // 控制最大保存量
        if (($count = self::getFormCount($siteID, $user_id)) > self::MAX_SAVE_SIZE) {
            self::getRedisHandle()->zRemRangeByRank($key, 0, 0);
        }

        // 每次刷新集合有效期为 7 天
        self::getRedisHandle()->expire($key, 604800);
    }

    /**
     * 获取有效的form_id
     * @param $user_id
     * @return mixed
     */
    public static function getFormId($siteID, $user_id)
    {

        $form_id = '';

        // 先清除无效form_id
        self::remInvalidFormId($siteID, $user_id);
        $form_id_array = self::getRedisHandle()->zRange(self::getFormKey($siteID, $user_id), 0, 0, false);
        if (!empty($form_id_array)) {
            self::delFormId($siteID, $user_id, $form_id = $form_id_array[0]);
        }

        return $form_id;
    }

    /**
     * 删除指定form_id
     * @param $user_id
     * @param $form_id
     * @return mixed
     */
    public static function delFormId($siteID, $user_id, $form_id)
    {
        return self::getRedisHandle()->zDelete(self::getFormKey($siteID, $user_id), $form_id);
    }

    /**
     * 移除无效（过期的）form_id
     * @param $user_id
     * @return mixed
     */
    public static function remInvalidFormId($siteID, $user_id)
    {
        return self::getRedisHandle()
            ->zRemRangeByScore(self::getFormKey($siteID, $user_id), 0, strtotime('-7 day'));
    }

    /**
     * 获取form_id有效个数
     * @param $user_id
     * @return mixed
     */
    public static function getFormCount($siteID, $user_id)
    {
        // 先清除无效form_id
        self::remInvalidFormId($siteID, $user_id);
        return self::getRedisHandle()->zSize(self::getFormKey($siteID, $user_id));
    }
}