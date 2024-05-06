<?php 

namespace Users;

Class PersonSkills
{
    public $free_f_skills;
    public $free_m_skills;
    public $free_p_skills;
    public $skill_zeroing = 0;
    public $sb1, $sb2, $sb3, $sb4, $sb5, $sb6, $sb7,
       $sb8, $sb9, $sb10, $sb11, $sb12, $sb13, $sb14 = 0;
    public $sm1, $sm2, $sm3, $sm4, $sm5, $sm6, $sm7 = 0;
    public $sp1, $sp2, $sp3, $sp4, $sp5, $sp6, $sp7,
       $sp8, $sp9, $sp10, $sp11, $sp12, $sp13, $sp14 = 0;
       
    function __construct(Person $person)
    {
        $this->free_f_skills = [
            'name' => 'Свободных боевых умений',
            'value' => $person->getAllDatas()['free_f_skills']
        ];
        $this->free_m_skills = [
            'name' => 'Свободных мирных умений',
            'value' => $person->getAllDatas()['free_m_skills']
        ];
        $this->free_p_skills = [
            'name' => 'Свободных умений',
            'value' => $person->getAllDatas()['free_p_skills']
        ];
        $this->skill_zeroing = [
            'name' => 'Сброс умений',
            'value' => $person->getAllDatas()['skill_zeroing']
        ];
        $this->sb1 = [
            'name' => 'Очки действия',
            'value' => $person->getAllDatas()['sb1']
        ];
        $this->sb2 = [
            'name' => 'Колкий удар',
            'value' => $person->getAllDatas()['sb2']
        ];
        $this->sb3 = [
            'name' => 'Владение ножами',
            'value' => $person->getAllDatas()['sb3']
        ];
        $this->sb4 = [
            'name' => 'Владение щитами',
            'value' => $person->getAllDatas()['sb4']
        ];
        $this->sb5 = [
            'name' => 'Владение мечами',
            'value' => $person->getAllDatas()['sb5']
        ];
        $this->sb6 = [
            'name' => 'Владение топорами',
            'value' => $person->getAllDatas()['sb6']
        ];
        $this->sb7 = [
            'name' => 'Владение булавами',
            'value' => $person->getAllDatas()['sb7']
        ];
        $this->sb8 = [
            'name' => 'Чтение книг',
            'value' => $person->getAllDatas()['sb8']
        ];
        $this->sb9 = [
            'name' => 'Усиление магии',
            'value' => $person->getAllDatas()['sb9']
        ];
        $this->sb10 = [
            'name' => 'Сопротивление Магии',
            'value' => $person->getAllDatas()['sb10']
        ];
        $this->sb11 = [
            'name' => 'Сопротивление Физическим повреждениям',
            'value' => $person->getAllDatas()['sb11']
        ];
        $this->sb12 = [
            'name' => 'Сопротивление Отравам',
            'value' => $person->getAllDatas()['sb12']
        ];
        $this->sb13 = [
            'name' => 'Сопротивление Электричеству',
            'value' => $person->getAllDatas()['sb13']
        ];
        $this->sb14 = [
            'name' => 'Сопротивление Огню',
            'value' => $person->getAllDatas()['sb14']
        ];
        $this->sm1 = [
            'name' => 'Атлетизм',
            'value' => $person->getAllDatas()['sm1']
        ];
        $this->sm2 = [
            'name' => 'Эрудиция',
            'value' => $person->getAllDatas()['sm2']
        ];
        $this->sm3 = [
            'name' => 'Тяжеловес',
            'value' => $person->getAllDatas()['sm3']
        ];
        $this->sm4 = [
            'name' => 'Скорость',
            'value' => $person->getAllDatas()['sm4']
        ];
        $this->sm5 = [
            'name' => 'Обаяние',
            'value' => $person->getAllDatas()['sm5']
        ];
        $this->sm6 = [
            'name' => 'Регенерация жизни',
            'value' => $person->getAllDatas()['sm6']
        ];
        $this->sm7 = [
            'name' => 'Регенерация маны',
            'value' => $person->getAllDatas()['sm7']
        ];
        $this->sp1 = [
            'name' => 'Целитель',
            'value' => $person->getAllDatas()['sp1']
        ];
        $this->sp2 = [
            'name' => 'Темное искусство',
            'value' => $person->getAllDatas()['sp2']
        ];
        $this->sp3 = [
            'name' => 'Удар в спину',
            'value' => $person->getAllDatas()['sp3']
        ];
        $this->sp4 = [
            'name' => 'Воровство',
            'value' => $person->getAllDatas()['sp4']
        ];
        $this->sp5 = [
            'name' => 'Кузнец',
            'value' => $person->getAllDatas()['sp5']
        ];
        $this->sp6 = [
            'name' => 'Рыбак',
            'value' => $person->getAllDatas()['sp6']
        ];
        $this->sp7 = [
            'name' => 'Шахтер',
            'value' => $person->getAllDatas()['sp7']
        ];
        $this->sp8 = [
            'name' => 'Ориентирование на местности',
            'value' => $person->getAllDatas()['sp8']
        ];
        $this->sp9 = [
            'name' => 'Экономист',
            'value' => $person->getAllDatas()['sp9']
        ];
        $this->sp10 = [
            'name' => 'Охотник',
            'value' => $person->getAllDatas()['sp10']
        ];
        $this->sp11 = [ 
            'name' => 'Алхимик',
            'value' => $person->getAllDatas()['sp11']
        ];
        $this->sp12 = [
            'name' => 'Добыча камней',
            'value' => $person->getAllDatas()['sp12']
        ];
        $this->sp13 = [
            'name' => 'Дровосек',
            'value' => $person->getAllDatas()['sp13']
        ];
        $this->sp14 = [
            'name' => 'Выделка кожи',
            'value' => $person->getAllDatas()['sp14']
        ];
    }

    public function getFightSkills() {
            $result = [
                'name'=> 'Боевые умения',
                'value' => [
                    $this->sb1,
                    $this->sb2,
                    $this->sb3,
                    $this->sb4,
                    $this->sb5,
                    $this->sb6,
                    $this->sb7,
                    $this->sb8,
                    $this->sb9
                ]
            ];
            return $result;
    }

    public function getResistanceSkills() {
        $result = [
            'name'=> 'Сопротивление',
            'value' => [
                $this->sb10,
                $this->sb11,
                $this->sb12,
                $this->sb13,
                $this->sb14
            ]
        ];
        return $result;
    }

    public function getDoubleSkills() {
        $result = [
            'name'=> 'Второстепенные умения',
            'value' => [
                $this->sm1,
                $this->sm2,
                $this->sm3,
                $this->sm4,
                $this->sm5,
                $this->sm6,
                $this->sm7
            ]
        ];
        return $result;
    }
    
    public function getPeacefulSkills() {
        $result = [
            'name'=> 'Второстепенные умения',
            'value' => [
                $this->sp1,
                $this->sp2,
                $this->sp3,
                $this->sp4,
                $this->sp5,
                $this->sp6,
                $this->sp7,
                $this->sp8,
                $this->sp9,
                $this->sp10,
                $this->sp11,
                $this->sp12,
                $this->sp13,
                $this->sp14
            ]
        ];
        return $result;
    }
}