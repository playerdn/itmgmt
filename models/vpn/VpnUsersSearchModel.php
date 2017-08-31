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
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'UID', 'REQUEST_DOC_ID'], 'integer'],
            [['OVPN_CONF_KIT', 'CERT_PASS', 'START_DATE', 'EXPIRATION', 'LAST_ACCESS', 'username'], 'safe'],
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
        ]);

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
            'LAST_ACCESS' => $this->LAST_ACCESS,
        ]);
        $query->joinWith('user');

        $query->andFilterWhere(['like', 'OVPN_CONF_KIT', $this->OVPN_CONF_KIT])
            ->andFilterWhere(['like', 'CERT_PASS', $this->CERT_PASS]);
        
        if($this->username) {
            $query->andWhere(['like', 'users.ADLogin', $this->username]);
        }

        $query->orderBy('users.ADLogin');
        return $dataProvider;
    }
}
