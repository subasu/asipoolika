<?php

namespace App\Console\Commands;

use App\Http\Controllers\CertificateController;
use App\Models\Certificate;
use App\Models\CertificateRecord;
use App\Models\Request2;
use App\Models\RequestRecord;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateReqAndCer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requestcertificate:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'accept and active field be 1';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //active product request
        $productRequests=Request2::where('request_type_id',3)->get();
        foreach($productRequests as $productRequest)
        {
            $all_count=RequestRecord::where('request_id',$productRequest->id)->count();
            $accept_count=RequestRecord::where([['request_id',$productRequest->id],['step',7],['refuse_user_id',null],['active',1]])->count();
            $has_certificate_count=RequestRecord::where([['request_id',$productRequest->id],['step',8]])->count();
            $refuse_count=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id','!=',null],['active',0]])->count();
            if($all_count==($accept_count+$refuse_count))
            {
                do{
                    $q=DB::table('requests')->where('id',$productRequest->id)->update([
                        'active'=>1
                    ]);
                }while($q==null);
//                $productRequest->msg='Yes';
//                    return redirect('admin/confirmProductRequestManagement');
            }
//            else
//                $productRequest->msg='No';
//            $productRequest->all_count=$all_count;
//            $productRequest->accept_count=$accept_count;
//            $productRequest->has_certificate_count=$has_certificate_count;
//            $productRequest->refuse_count=$refuse_count;
//active the certificates of this request
            $certificates=Certificate::where('request_id',$productRequest->id)->get();

            foreach ($certificates as $certificate) {
                $all_c_count=CertificateRecord::where('certificate_id',$certificate->id)->count();
                $finished_c_count=CertificateRecord::where([['certificate_id',$certificate->id],['step',5]])->count();
                if($all_c_count==$finished_c_count)
                {
                    do{
                        $q=Certificate::where('id',$certificate->id)->update([
                            'active'=>1
                        ]);
                    }while($q==null);
                }
            }
        }
        //active service request
        $productRequests=Request2::where('request_type_id',2)->get();
        foreach($productRequests as $productRequest)
        {
            $all_count=RequestRecord::where('request_id',$productRequest->id)->count();
            $accept_count=RequestRecord::where([['request_id',$productRequest->id],['step',7],['refuse_user_id',null],['active',1]])->count();
            $has_certificate_count=RequestRecord::where([['request_id',$productRequest->id],['step',8]])->count();
            $refuse_count=RequestRecord::where([['request_id',$productRequest->id],['refuse_user_id','!=',null],['active',0]])->count();
            if($all_count==($accept_count+$refuse_count))
            {
                do{
                    $q=DB::table('requests')->where('id',$productRequest->id)->update([
                        'active'=>1
                    ]);
                }while ($q==null);
//                $productRequest->msg='Yes';

            }

//            else
//                $productRequest->msg='No';
//            $productRequest->all_count=$all_count;
//            $productRequest->accept_count=$accept_count;
//            $productRequest->has_certificate_count=$has_certificate_count;
//            $productRequest->refuse_count=$refuse_count;

//active the certificates of this request
            $certificates=Certificate::where('request_id',$productRequest->id)->get();
            foreach ($certificates as $certificate) {
                $all_c_count=CertificateRecord::where('certificate_id',$certificate->id)->count();
                $finished_c_count=CertificateRecord::where([['certificate_id',$certificate->id],['step',5]])->count();
                if($all_c_count==$finished_c_count)
                {
                    do{
                        $q=Certificate::where('id',$certificate->id)->update([
                            'active'=>1
                        ]);
                    }while ($q==null);
                }
            }
        }


//        $certificate=new CertificateController();
//        $certificates=$certificate->acceptedCertificatesActive();
//        foreach($certificates as $certificate)
//        {
//            $certificate_records_count=CertificateRecord::where('certificate_id',$certificate->id)->count();
//            $accepted_certificate_record_count=CertificateRecord::where([['certificate_id',$certificate->id],['step',5]])->count();
//            if($certificate_records_count==$accepted_certificate_record_count)
//            {
//                Certificate::where('id',$certificate->id)->update([
//                    'active'=>1
//                ]);
//            }
//        }
    }
}
