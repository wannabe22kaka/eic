

import org.apache.hadoop.io.WritableComparator;
import org.apache.hadoop.record.Buffer;

import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

import java.io.IOException;

import org.apache.hadoop.io.DataInputBuffer;
import org.apache.hadoop.io.WritableComparable;

public class SortJsonDataComparator extends WritableComparator {
	public static DataInputBuffer buffer = new DataInputBuffer();
	public static SortJsonData    key1   = new SortJsonData();
	public static SortJsonData    key2   = new SortJsonData();
	protected SortJsonDataComparator(){
		super(SortJsonData.class,true);
	}

	@Override
	public int compare(WritableComparable w1, WritableComparable w2){
		SortJsonData d1 = (SortJsonData)w1;
		SortJsonData d2 = (SortJsonData)w2;
		

		//compare sum
		//내림 차순을 위해서 -1을 곱한다.
		int cmp = d1.getSum().compareTo(d2.getSum()) * -1;

	
		if (cmp != 0){
			return cmp;
		}
		
	
		
		JSONObject jsonObject1 = JSONObject.fromObject(JSONSerializer.toJSON(d1.getJsonString()));
		JSONObject jsonObject2 = JSONObject.fromObject(JSONSerializer.toJSON(d2.getJsonString()));
		
		
		
		Integer d1Ordering  = new Integer((int)jsonObject1.getString("keyword").charAt(0));
		Integer d2Ordering = new Integer((int)jsonObject2.getString("keyword").charAt(0));
		
		cmp =  d1Ordering.compareTo(d2Ordering) * -1; 
		
		if (cmp != 0){
			return cmp;
		}
		
		
		
		d1Ordering = new Integer(jsonObject1.getString("keyword").length());
		d2Ordering = new Integer(jsonObject2.getString("keyword").length());
		cmp =  d1Ordering.compareTo(d2Ordering) * -1; 
		
		if (cmp != 0){
			return cmp;
		}
		
		
		
		d1.setMorpheme(jsonObject1.getString("morpheme"));
		d2.setMorpheme(jsonObject2.getString("morpheme"));
		d1Ordering = new Integer(getOrderingOfMorpheme(d1.getMorpheme()));
		d2Ordering = new Integer(getOrderingOfMorpheme(d2.getMorpheme()));
		return d1Ordering.compareTo(d2Ordering) * -1; 
		
	}
	
	int getOrderingOfMorpheme(String _morpheme)
	{
	/*	
		this.addData("NNP");
		this.addData("NNG");
		this.addData("SW");
		this.addData("SH");
		this.addData("NN");
		this.addData("SL");
		this.addData("SN");
		this.addData("NA");
		*/
		int order = 0;
		switch(_morpheme)
		{
		 case "NA":
			 order = 7;
			 break;
		 case "NNP":
			 order = 6;
			 break;
		 case "NNG":
			 order = 5;
			 break;
		 case "NN":
			 order = 4;
			 break;
		 case "SH":
			 order = 3;
			 break;
		 case "SL":
			 order = 2;
			 break;
		 case "SN":
			 order = 1;
			 break;
		 case "SW":
			 order = 0;
			 break;
		}
		
		return order;
		
		
		
	}

}
