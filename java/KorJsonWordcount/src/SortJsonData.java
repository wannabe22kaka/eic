

import org.apache.hadoop.io.WritableComparable;
import org.apache.hadoop.io.WritableUtils;

import java.io.DataInput;
import java.io.DataOutput;
import java.io.IOException;

public class SortJsonData implements WritableComparable<SortJsonData>{
	private String  JsonString = new String();
	private String  Keyword = new String();
	private String  Morpheme = new String();
	private Integer Sum 		= new Integer(0);
	private Integer Direction 		= new Integer(0);
	
	public SortJsonData(){
		
	}
	
	public SortJsonData(String _json, Integer _sum){
		this.JsonString	= _json;
		this.Sum			= _sum;
	}
	
	public String getJsonString(){
		return JsonString;
	}
	
	public void   setJsonString(String _json){
		this.JsonString = _json;
	}
	
	public String getKeyword(){
		return Keyword;
	}
	
	public void   setKeyword(String _json){
		this.Keyword = _json;
	}
	
	public Integer getSum(){
		return Sum;
	}
	
	public void setSum(Integer _sum){
		this.Sum = _sum;
	}
	
	public String getMorpheme(){
		return Morpheme;
	}
	
	public void setMorpheme(String _morpheme){
		this.Morpheme = _morpheme;
	}
	
	public Integer getDirection(){
		return Direction;
	}
	
	public void   setDirection(int _direction){
		this.Direction = _direction;
	}
	
	
	@Override
	public String toString(){
		return this.JsonString;
	}
	
	@Override
	public void readFields(DataInput in) throws IOException{
		JsonString = WritableUtils.readString(in);
	    Sum = in.readInt();
	}
	
	@Override
	public void write(DataOutput out) throws IOException{
		WritableUtils.writeString(out,JsonString);
		out.writeInt(Sum);
	}
	
	@Override
	public int compareTo(SortJsonData _data){
		int result = Sum.compareTo(_data.getSum());
		return result;
	}
	
	
	
}
