<?php

namespace app\models\vpn;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\vpn\VpnUsersRecord;

/**
 * VpnUsersSearchModel represents the model behind the search form about `app\models\vpn\VpnUsersRecord`.
 */
class VpnUsersSearchModel extends VpnUsersRecord
{
    public $username;
    public $ipAddresses;
    public $workstations;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'UID', 'REQUEST_DOC_ID'], 'integer'],
            [['OVPN_CONF_KIT', 'CERT_PASS', 'START_DATE', 'EXPIRATION', 
                'LAST_ACCESS', 'username', 'ipAddresses', 'workstations'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = VpnUsersRecord::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                //'pageSize' => 40,
            ],
        ]);

        $query->joinWith('user');
        $query->joinWith('vpnUserIpLinks');
        $query->join('LEFT OUTER JOIN', 'vpn_ip_pool', 'vpn_ip_pool.id = vpn_user_ip_links.vpn_ip_id');
        $query->joinWith('vpnRdpAccesses');
        $query->join('LEFT OUTER JOIN', 'workstations', 'workstations.id = vpn_rdp_access.WSID');
        
        // Add sorting
        $dataProvider->sort->attributes['username'] = [
            'asc' => ['users.ADLogin' => SORT_ASC],
            'desc' => ['users.ADLogin' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['LAST_ACCESS'] = [
            'asc' => ['LAST_ACCESS' => SORT_ASC],
            'desc' => ['LAST_ACCESS' => SORT_DESC]
        ];
        // Set default sorting for grid!
        $dataProvider->sort->defaultOrder['username'] = SORT_ASC;

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ID' => $this->ID,
            'UID' => $this->UID,
            'REQUEST_DOC_ID' => $this->REQUEST_DOC_ID,
            'START_DATE' => $this->START_DATE,
            'EXPIRATION' => $this->EXPIRATION,
        ]);
        
        if($this->LAST_ACCESS) {
            $query->andWhere(['like', 'LAST_ACCESS', $this->LAST_ACCESS]);
        }

        $query->andFilterWhere(['like', 'OVPN_CONF_KIT', $this->OVPN_CONF_KIT])
            ->andFilterWhere(['like', 'CERT_PASS', $this->CERT_PASS]);
        
        if($this->username) {
            $query->andWhere(['like', 'users.ADLogin', $this->username]);
        }
        if($this->ipAddresses) {
            $query->andWhere(['like', 'vpn_ip_pool.ip', $this->ipAddresses]);
        }
        if($this->workstations) {
            $query->andWhere(['like', 'workstations.name', $this->workstations]);
        }
        $query->distinct();
        return $dataProvider;
    }
}
