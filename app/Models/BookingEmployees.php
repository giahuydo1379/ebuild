<?php 
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
  
class BookingEmployees extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'booking_employees';

    protected $primaryKey = 'id';
     
	protected $fillable = ['booking_id','employee_id','salary','rating','status'];

	public static function key_booking_status_update(){
		return [
			5 => 'done',
			6 => 'cancel'
		];
	}

	public static function update_status($booking_id,$booking_status_id){
		$status = self::key_booking_status_update();
		if(!array_key_exists($booking_status_id, $status)){
			return false;
		}
		return self::where('booking_id',$booking_id)->update(['status' =>$status[$booking_status_id]]);
	}
}
?>