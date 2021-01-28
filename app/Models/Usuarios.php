<?php

namespace App\Models;

use App\Models\Traits\UserACLTrait;
use App\Models\Traits\UserPermissionsTrait;
use App\Models\TermosUsuarios;
use Illuminate\Foundation\Auth\User AS Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuarios extends Authenticatable implements JWTSubject
{
    use Notifiable, UserPermissionsTrait;

    protected $table = 'Usuarios';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'cpf', 'nome', 'data_expiracao', 'senha_atual', 'email', 'coligada', 'telefone', 'status', 'id_ut_cc', 'ut_cc',
        'perfil', 'possui_ad', 'sync'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ 'senha_atual', 'sync' ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = ['id','cpf', 'nome', 'data_expiracao', 'email', 'coligada', 'telefone', 'status', 'id_ut_cc', 'ut_cc',
        'perfil', 'possui_ad'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function profile() {
        return $this->hasOne(Perfil::class, 'id', 'perfil');
    }

    public function scopePermissoes($query) {
        return Permissao::where('perfil', $this->attributes['perfil'])->where('acesso', 1)->get();
    }

    public function scopePermissoesCustom()
    {
        return PermissaoCustom::where('usuario_id', $this->attributes['id'])->where('acesso', 1)->get();
    }

    public function condutor() {
        return $this->hasOne(Condutores::class, 'id_usuario', 'id');
    }

    public function usuarioRepresentante() {
        return $this->hasMany(UsuariosRepresentantes::class, 'id_usuario', 'id');
    }

    public function gestor() {
        return $this->hasMany(GestoresUt::class, 'id_gestor', 'id');
    }

    public function ut()
    {
        return $this->hasOne(UT::class, 'id', 'id_ut_cc');
    }

    public function termo()
    {
        return $this->hasMany(TermosUsuarios::class, 'id_usuario','id');
    }

    public function scopeMe($query)
    {
        $data = [];

        $data["usuario"] = $this->getAttributes();

        $data["gestor"] = $this->isManager($this->attributes["id"]) ? 'Sim' : 'Não';

        $data["representante"] = $this->isRepresentative($this->attributes["id"]) ? 'Sim' : 'Não';
        $termo = TermosUsuarios::where('id_usuario',$this->attributes["id"])->first();
         
        if( $termo!=null && $termo->status == 1){
            $data["termo"] = 1;
        }else{
            $data["termo"] = 0;
        }
        
        $data['condutor'] = Condutores::select('id','cnh','data_vencimento_cnh')->where('id_usuario',$this->attributes['id'])->get();

        $perfil = Perfil::find($this->attributes['perfil']);
        $data["perfil"] = $perfil;

        $permissaoCustom = PermissaoCustom::with('regras')
            ->where('usuario_id', $this->attributes['id'])
            ->where('acesso', 1)
            ->get(["id", "regra"]);
        $permissao = Permissao::with('regras')
            ->where('perfil', $this->attributes['perfil'])
            ->where('acesso', 1)
            ->get(["id", "regra"]);

        if ($permissaoCustom->count() > 0) {
            $data["permissoes"] = ["data" => $permissaoCustom];
        } else {
            $data["permissoes"] = ["data" => $permissao];
        }
        $ut = UT::find($this->attributes['id_ut_cc']);
        $data["ut"] = $ut;

        $gestores = GestoresUt::with('ut')->where('id_ut_cc', $ut->id)->get();
        $data["gestores"] = $gestores;

        return $data;
    }

    public function manager()
    {
        return $this->belongsToMany(UT::class, 'Gestores_UT', 'id_gestor', 'id_ut_cc');
    }

    public function representative()
    {
        return $this->belongsToMany(UT::class, 'Usuario_Representante', 'id_usuario', 'id_ut_permitida');
    }

    public function getCpfAttribute()
    {
        $valor = mb_substr($this->attributes['cpf'], 0, 3);
        return $valor . "********";
    }   
}
