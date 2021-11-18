<?php

namespace App\Exports;

use App\SaleDealer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class SaledealerExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function Collection() {
    	// return SaleDealer::select('sale_dealer_code', 'sale_dealer_name')->get();

    	$query = DB::table('sale_dealers')
            ->join('dealers', 'sale_dealers.dealer_id', '=', 'dealers.id')
            ->select(
                'sale_dealers.id as id',
                'sale_dealers.sale_dealer_code as code',
                'sale_dealers.sale_dealer_name as sale_dealer_name',
                'sale_dealers.sale_dealer_nickname as nickname',
                'sale_dealers.sale_dealer_tel as tel',
                'dealers.dealer_ids_code as ids',
                'dealers.dealer_legacy_code as legacy',
                'dealers.dealer_zone as zone',
                'dealers.dealer_area as area',
                'dealers.dealer_dlr as dlr',
                'dealers.dealer_name as dealer_name'
                
            )
            ->where('sale_dealers.sale_dealer_status','=',1);


        if(isset($request->text_search_sale_dealer) && ($request->text_search_sale_dealer != '')){
            $query->where([
                ['sale_dealers.sale_dealer_code','like','%'.$request->text_search_sale_dealer.'%','or'],
                ['sale_dealers.sale_dealer_name','like','%'.$request->text_search_sale_dealer.'%','or'],
                ['sale_dealers.sale_dealer_nickname','like','%'.$request->text_search_sale_dealer.'%','or'],
                ['sale_dealers.sale_dealer_tel','like','%'.$request->text_search_sale_dealer.'%','or'],
                ['dealers.dealer_ids_code','like','%'.$request->text_search_sale_dealer.'%','or'],
                ['dealers.dealer_legacy_code','like','%'.$request->text_search_sale_dealer.'%','or'],
                ['dealers.dealer_zone','like','%'.$request->text_search_sale_dealer.'%','or'],
                ['dealers.dealer_area','like','%'.$request->text_search_sale_dealer.'%','or'],
                ['dealers.dealer_dlr','like','%'.$request->text_search_sale_dealer.'%','or'],
                ['dealers.dealer_name','like','%'.$request->text_search_sale_dealer.'%','or']
            ]);
        }
        if(isset($request->dlr_search_sale_dealer)){
            $query->where('dealers.dealer_dlr','like',$request->dlr_search_sale_dealer);
        }
        if(isset($request->zone_search_sale_dealer)){
            $query->where('dealers.dealer_zone','like',$request->zone_search_sale_dealer);
        }
        if(isset($request->area_search_sale_dealer)){
            $query->where('dealers.dealer_area','like',$request->area_search_sale_dealer);
        }

        if(isset($request->event_id)){
            $query->where('dealers.event_id','like',$request->event_id);
        }

         if(isset($test)){
            $query->where('dealers.event_id','like',$test);
        }


        $sale_dealer = $query->get();

        // dd($sale_dealer->toArray());

        return $sale_dealer;
    }

    public function headings(): array
    {
        return [
            '#',
            'ID Sale',
            'Sale Name',
            'Nickname',
            'Mobile',
            'IDS',
            'Legecy',
            'Zone',
            'Area',
            'DLR',
            'DLR Name'
        ];
    }

}
