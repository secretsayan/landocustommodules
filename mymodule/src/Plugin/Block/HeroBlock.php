<?php

namespace Drupal\mymodule\Plugin\Block ;

use Drupal\Core\Block\BlockBase ;

/**
 * This provides a block called "Example Hero Block"
 * 
 * @Block(
 * id= "mymodule_hero",
 * admin_label=@Translation("Example Hero Block")
 * )
 */
class HeroBlock extends BlockBase
{

    /**
     * Build function.
     */
    public function build()
    {
        $table = [
            '#type' => 'table',
            '#header' => [
                $this->t('Name'),
                $this->t('Mobile')
            ]
        ];

        $persons = [
            ['name' => 'Sayan','mobile' => 100],
            ['name' => 'Ronaldo','mobile' => 100],
            ['name' => 'Messi','mobile' => 100],
            ['name' => 'Zidane','mobile' => 100],
            ['name' => 'Mane','mobile' => 100]

        ];

        foreach ($persons as $person) {
            $table[] = [
                'name' => [
                    '#type' => 'markup',
                    '#markup' => $this->t($person['name'])
                ],
                'mobile' => [
                    '#type' => 'markup',
                    '#markup' => $person['mobile']
                ]
            ];
        }
        return $table;
    }

}
