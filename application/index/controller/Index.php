<?php
namespace app\index\controller;

use app\base\controller\Base;
use app\common\model\Ad;
use app\common\model\AdCategory;
use app\common\model\Member;
use app\common\model\MemberWeixin;
use app\base\controller\BrowserCheck;
use app\base\controller\Weixin;
use app\common\model\SiteLink;
use app\common\model\SlideCategory;
use app\common\model\Slide;
use app\common\model\Teacher;
use app\common\model\Article;
use app\common\model\Course;
use app\common\model\Student;
use app\common\model\StudentCategory;

class Index extends Base
{
    /**
     * 网站首页
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        // 首页幻灯片
        $slide_category_info = SlideCategory::get(['unique_code'=>'home_top']);
        $slide_info = new Slide();
        $slide_data = $slide_info->where(['site_id'=>$this->site_id,'category_id'=>$slide_category_info['id']]) -> select();
        $this->assign('slide',$slide_data);

        // 首页顶部广告
        $ad_category = AdCategory::get(['unique_code'=>'home_top']);
        $ad_data = Ad::get(['site_id'=>$this->site_id,'category_id'=>$ad_category['id']]);
        $this->assign('home_top_ad',$ad_data);

        // 所有课程
        $course_info = new Course();
        $course_data = $course_info->where(['site_id'=>$this->site_id,'status'=>1])-> order('sort asc') ->limit(12) ->select();
        $this->assign('course',$course_data);

        // 判断是否为微信浏览器
        $user_browser = new BrowserCheck();
        $user_browser_info = $user_browser->info();
        if($user_browser_info=='wechat'){
            $weixin_user_info = new Weixin();
            $username = session('username');
            $member_data = Member::get(['username' => $username]);

            $openid = $weixin_user_info->info($this->site_id,$member_data['id']);
            $this->assign('openid',$openid);
            // 获取会员信息
            $member_weixin_info = MemberWeixin::get(['openid'=>$openid]);
            $member_weixin_id = $member_weixin_info['id'];

            $member_info = Member::get(['weixin_id'=>$member_weixin_id]);
            if(!empty($member_info)){
                $member_info['name'] = $member_info['real_name'];

                $this->assign('member',$member_info);
            }else{
                $member_weixin_info['name'] = $member_weixin_info['nickname'];
                $this->assign('member',$member_weixin_info);
            }
            return $this->fetch($this->template_path);
        }




        // 最新课程

        // 最热课程

        // 推荐课程

        //  老师列表
        $teacher_info = new Teacher();
        $teacher_data = $teacher_info->where(['site_id'=>$this->site_id,'status'=>1])-> order('sort asc') ->limit(4) ->select();
        $this->assign('teacher',$teacher_data);

        // 学员点评列表
        $student_info = new Student();
        $student_data = $student_info->where(['site_id'=>$this->site_id,'status'=>1])-> order('sort asc') ->limit(6) ->select();
        foreach ($student_data as $student_list) {
            $student_category_id = $student_list['category_id'];
            $student_category_data = StudentCategory::get($student_category_id);
            $student_category_title = $student_category_data['title'];
            $student_list['category_title'] = $student_category_title;
        }
        $this->assign('student',$student_data);

        // 资讯列表
        $article_info = new Article();
        $article_data = $article_info->where(['site_id'=>$this->site_id,'status'=>1])-> order('sort asc') ->limit(5) ->select();
        $this->assign('article',$article_data);

        // 底部链接

        // 友情链接
        $site_link_data = new SiteLink();
        $site_link = $site_link_data->where(['site_id' => $this->site_id])->select();
        $this->assign('site_link',$site_link);

        // 底部版权



        return $this->fetch($this->template_path);
    }

}
