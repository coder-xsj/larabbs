<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    // 控制是否返回用户的 email phone 字段信息
    protected $showSensitiveFields = false;

    public function toArray($request)
    {
        if(!$this->showSensitiveFields){
            $this->resource->makeHidden(['email', 'phone']);
        }
        $data = parent::toArray($request);
        // bound_phone 是否绑定手机；
        // bound_wechat 是否绑定微信。
        $data['bound_phone'] = $this->resource->phone ? true : false;
        $data['bound_wechat'] = ($this->resource->weixin_unionid || $this->resource->weixin_openid) ? true : false;
        // 当模型加载了 role 关系时，显示角色相关数据
        $data['roles'] = RoleResource::collection($this->whenLoaded('roles'));

        return $data;
    }

    public function showSensitiveFields(){
        $this->showSensitiveFields = true;
        return $this;
    }
}
