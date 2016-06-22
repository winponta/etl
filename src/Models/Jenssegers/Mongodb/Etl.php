<?php

namespace Winponta\ETL\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Etl extends Model {
    
    public function touch() {
        if ($this->created_at == null) {
            $this->created_at = $this->asDateTime(\Carbon\Carbon::now());
        }
        
        $this->updated_at = $this->asDateTime(\Carbon\Carbon::now());
    }
    
}
