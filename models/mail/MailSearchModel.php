<?php

namespace app\models\mail;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mail\MailRecord;

/**
 * MailSearchModel represents the model behind the search form about `app\models\mail\MailRecord`.
 */
class MailSearchModel extends MailRecord
{
    public $fio;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'IsLocal'], 'integer'],
            [['name_f', 'name_i', 'name_o', 'guid', 'login', 'E_mail', 'passwd', 
              'ip', 'date_cr', 'tel', 'komn', 'otdel', 
              'aliases', 'IsDismiss', 'fio'], 'safe'],
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
        $query = MailRecord::find();//->where('guid is not null');

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
          'query' => $query,
          'sort' => ['attributes' => [
                        'fio' => [
                            'asc' => ['name_f' => SORT_ASC],
                            'desc' => ['name_f' => SORT_DESC],
                        ],
                        'E_mail',
                        'login',
                        'spam_f',
                        'greylist',
                        'visible_mail',
                        'IsEnabled',
                        'aliases',
                ]
            ]
        ]);

//        $dataProvider->sort->attributes['fio'] = [
//                    'asc' => ['name_f' => SORT_ASC],
//                    'desc' => ['name_f' => SORT_DESC],
//                ];

        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_cr' => $this->date_cr,
            'IsLocal' => $this->IsLocal,
            'visible_mail' => $this->visible_mail,
        ]);

        $query->andFilterWhere([
                'or',
                ['like', 'name_f', $this->fio],
                ['like', 'name_o', $this->fio],
                ['like', 'name_i', $this->fio]
            ])
            ->andFilterWhere(['like', 'guid', $this->guid])
            ->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'E_mail', $this->E_mail])
            ->andFilterWhere(['like', 'passwd', $this->passwd])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'spam_f', $this->spam_f])
            ->andFilterWhere(['like', 'greylist', $this->greylist])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'komn', $this->komn])
            ->andFilterWhere(['like', 'otdel', $this->otdel])
            ->andFilterWhere(['like', 'aliases', $this->aliases])
            ->andFilterWhere(['like', 'IsEnabled', $this->IsEnabled])
            ->andFilterWhere(['like', 'IsDismiss', $this->IsDismiss]);

        return $dataProvider;
    }
}
