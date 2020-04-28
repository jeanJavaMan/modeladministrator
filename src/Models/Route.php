<?php

    namespace Jeanderson\modeladministrator\Models;

    use App\User;
    use Eloquent;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
     * Class Route
     * @property int id
     * @property int modelconfigs_id
     * @property string url
     * @property string method
     * @property string type
     * @property bool visible_to_everyone
     * @property string permissions
     * @property string functions
     * @package Jeanderson\modeladministrator\Models
     */
    class Route extends Eloquent
    {
        protected $fillable = ["modelconfigs_id","url","method","type",'functions','visible_to_everyone','permissions'];
        protected $table = "routes";

        /**
         * @return BelongsTo
         */
        public function modelConfig(){
            return $this->belongsTo(ModelConfig::class,"modelconfigs_id");
        }

        /**
         * @return ModelConfig|mixed
         */
        public function modelConfig_cache(){
            return \Cache::rememberForever("route-model-".$this->id,function (){
                return $this->modelConfig()->first();
            });
        }

        public function users_permissions(){
            return $this->belongsToMany(User::class,"routes_users_permissions","route_id","user_id");
        }

        /**
         * Verificar se o usuário logado tem permissão para acessar está rota
         * @return bool
         */
        public function checkIfUserHaspermission(){
            if(!is_null($this->permissions)){
                $permissions = explode(",",$this->permissions);
                return auth()->user()->hasAnyPermission($permissions);
            }else{
                return $this->visible_to_everyone;
            }
        }

        /**
         * Adicionar um usuário que terá acesso a está rota.
         * @param $user_id
         * @return array
         */
        public function add_user_permission($user_id){
            return $this->users_permissions()->attach($user_id);
        }

        /**
         * Atualizar todos os usuário que teram acesso.
         * @param $users
         * @return array
         */
        public function update_all_users_permission($users){
            \Cache::flush();
            return $this->users_permissions()->sync($users);
        }
    }
