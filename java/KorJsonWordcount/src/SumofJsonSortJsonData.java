

import java.io.IOException;
import java.nio.charset.Charset;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Iterator;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.LongWritable;
import org.apache.hadoop.io.NullWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.*;
import org.apache.hadoop.mapreduce.Mapper.Context;
import org.apache.hadoop.mapreduce.lib.input.FileInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;
import org.apache.hadoop.mapreduce.lib.input.TextInputFormat;
import org.apache.hadoop.mapreduce.lib.output.TextOutputFormat;

import com.alexholmes.json.mapreduce.MultiLineJsonInputFormat;
import com.WordFilter.PositiveorNegativeWord;

import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

public class SumofJsonSortJsonData {
	
	

	
	public static String JsonStringConvertToPlainString(SortJsonData _data){

		return  _data.getJsonString();
	}
	
	public  static class SumofJsonMapperWithSortJsonData extends Mapper 
	<LongWritable,Text,SortJsonData,IntWritable>{
		
		private SortJsonData outputkey = new SortJsonData();


	
		public void map(LongWritable key, Text jsonstring, Context context
                ) throws IOException, InterruptedException {
			JSONObject jsonObject = JSONObject.fromObject(JSONSerializer.toJSON(jsonstring.toString()));
			outputkey.setSum(jsonObject.getInt("sum"));		
			outputkey.setJsonString(jsonstring.toString());
			outputkey.setMorpheme(jsonObject.getString("morpheme"));
			outputkey.setKeyword(jsonObject.getString("keyword"));
			context.write(outputkey, new IntWritable(outputkey.getSum()));
		}
	}

	public static class SumofJsonReducerWithSortJsonData 
	extends Reducer<SortJsonData,IntWritable,NullWritable,Text> {
			@Override
			public void reduce(SortJsonData jsondata, Iterable<IntWritable> values,
	                  Context context
	                  ) throws IOException, InterruptedException {

				context.write(null,new Text(jsondata.getJsonString()));
				
			}
			@Override
			protected void cleanup(Context context)
	                throws IOException, InterruptedException{
				
			
				

			}

	}

		  
	
}
