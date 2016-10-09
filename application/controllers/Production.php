<?php
/**
 * @author: Spencer
 * Date: 10/2/2016
 * Time: 2:49 PM
 */
class Production extends Application {

    public function index() {
        $this->data['header'] = 'header';
        $this->data['pagebody'] = 'production/index';

        $this->load->model('Recipe');
        $recipes = $this->Recipe->all();

        $this->data['recipes'] = $recipes;
        $this->render();
    }

    public function show($id = 0) {
        $this->load->model('Recipe');
        $recipe = $this->Recipe->get($id);

        if (is_null($recipe))
            show_404();

        $this->data['header'] = 'header';
        $this->data['pagebody'] = 'production/show';

        $ingredients = array();
        
        foreach($recipe['ingredients'] as $key => $inStock){
            if ($this->checkStock($key,$inStock)) {
                $ingredients[] = array('name' => $this->getName($key), 'inStock' => $inStock, 'oos' => "OUT OF STOCK");
            } else {
                $ingredients[] = array('name' => $this->getName($key), 'inStock' => $inStock, 'oos' => "");
            }
        }
        
        $this->data['code'] = $recipe['code'];
        $this->data['description'] = $recipe['description'];
        $this->data['ingredients'] = $ingredients;
        $this->data['backUrl'] = base_url() . "production";       
        
        $this->render();
    }
    
    public function checkStock($key, $inStock) {
        $this->load->model('Supplies');
        
        $count = $this->Supplies->get($key)['onHand'];
        return $count < $inStock;
    }
    
    public function getName($id) {
        $this->load->model('Supplies');
        
        $item = $this->Supplies->get($id);
        return $item['name'];
    }

}

