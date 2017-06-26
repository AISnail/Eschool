<?php

namespace app\www\helper;

/**
 * StaticInit
 * @author   ShaoWei Pu <pushaowei0727@gmail.com>
 * @date     2017/6/20
 * @license  Mozilla
 */
trait StaticInit
{
    public function options( $page )
    {
        $common = [
            'style'  => [
                'css/base.css',
                'css/style-min.css',
            ],
            'script' => [
                'js/jquery-1.7.2.min.js',
            ]
        ];
        switch ( $page ) {
            case 'meeting.index':
                $options = [
                    'title'       => '中国高等教育学会-分支机构',
                    'keywords'    => '',
                    'description' => '',
                    'style'       => [

                    ],
                    'script'      => [

                    ]
                ];
                break;
            case 'user.register':
                $options = [
                    'title'       => '中国高等教育学会-用户注册',
                    'keywords'    => '',
                    'description' => '',
                    'style'       => [

                    ],
                    'script'      => [

                    ]
                ];
                break;
            case 'meeting.notice':
                $options = [
                    'title'       => '中国高等教育学会-会议通知',
                    'keywords'    => '',
                    'description' => '',
                    'style'       => [

                    ],
                    'script'      => [

                    ]
                ];
                break;
            case 'meeting.apply':
                $options = [
                    'title'       => '中国高等教育学会-立即报名',
                    'keywords'    => '',
                    'description' => '',
                    'style'       => [

                    ],
                    'script'      => [
                        'js/apply/understand_apply.js',
                    ]
                ];
                break;
            case 'apply.verify':
                $options = [
                    'title'       => '中国高等教育学会-提交订单',
                    'keywords'    => '',
                    'description' => '',
                    'style'       => [

                    ],
                    'script'      => [

                    ]
                ];
                break;
            case 'apply.triumph':
                $options = [
                    'title'       => '中国高等教育学会-报名',
                    'keywords'    => '',
                    'description' => '',
                    'style'       => [

                    ],
                    'script'      => [

                    ]
                ];
                break;
            default:
                break;
        }
        if ( isset($options) ) {
            return array_merge_recursive($common, $options);
        }
    }
}