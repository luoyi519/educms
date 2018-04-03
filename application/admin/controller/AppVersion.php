<?php
/**
 * Created by PhpStorm.
 * User: tzx
 * Date: 2016/10/25
 * Time: 22:48
 */
namespace app\admin\controller;

use think\Request;
use app\base\model\AppVersion as AppVersionModel;

class AppVersion extends AdminBase
{
    /**
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(Request $request)
    {
        $site_id = $this->site_id;
        // 找出列表数据
        $post_title = $request->param('title');
        $data = new AppVersionModel;
        if(!empty($post_title)){
            $data_list = $data->where(['status' => 1, 'title' => ['like','%'.$post_title.'%']])->select();
        }else{
            $data_list = $data->where(['site_id'=>$site_id,'status'=>1])->select();
        }
        $data_count = count($data_list);
        $this->assign('data_count',$data_count);

        $this->assign('data_list',$data_list);

        return $this->fetch($this->template_path);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        return $this->fetch($this->template_path);
    }

    /**
     * @param Request $request
     */
    public function save(Request $request)
    {
        $post_site_id = $request->post('site_id');
        $post_title = $request->post('title');
        $post_number = $request->post('number');
        $post_android_url = $request->post('android_url');
        $post_ios_url = $request->post('ios_url');
        $post_status= $request->post('status');

        if($post_title==''){
            $this->error('标题不能为空');
        }
        $user = new AppVersionModel;
        $user['site_id'] = $post_site_id;
        $user['title']    = $post_title;
        $user['number'] = $post_number;
        $user['android_url'] = $post_android_url;
        $user['ios_url']    = $post_ios_url;
        $user['status'] = $post_status;

        if ($user->save()) {
            $this->success('新增成功', '/admin/app_version/index');
        } else {
            $this->error('操作失败');
        }

    }

    /**
     * @param $id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function edit($id)
    {
        // 获取信息
        $data_list = AppVersionModel::get($id);
        $this->assign('data',$data_list);

        return $this->fetch($this->template_path);
    }

    /**
     * @param Request $request
     * @throws \think\exception\DbException
     */
    public function update(Request $request)
    {
        $post_id = $request->post('id');
        $post_site_id = $request->post('site_id');
        $post_title = $request->post('title');
        $post_number = $request->post('number');
        $post_android_url = $request->post('android_url');
        $post_ios_url = $request->post('ios_url');
        $post_status= $request->post('status');

        if($post_title=='' or $post_id==''){
            $this->error('幻灯片名称不能为空');
        }

        $user = AppVersionModel::get($post_id);
        $user['site_id'] = $post_site_id;
        $user['title']    = $post_title;
        $user['number'] = $post_number;
        $user['android_url'] = $post_android_url;
        $user['ios_url']    = $post_ios_url;
        $user['status'] = $post_status;

        if ($user->save()) {
            $this->success('保存成功', '/admin/app_version/index');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * @param $id
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        $user = AppVersionModel::get($id);
        if ($user) {
            $user->delete();
            $this->success('删除成功', '/admin/app_version/index');
        } else {
            $this->error('删除失败');
        }
    }

}