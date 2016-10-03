import java.io.IOException;
import java.sql.SQLException;

import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.LongWritable;
import org.apache.hadoop.io.NullWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Mapper;
import org.apache.hadoop.mapreduce.Reducer;
import org.apache.hadoop.mapreduce.Reducer.Context;

import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

import com.DBControll.imfDBController;
import com.WordFilter.FindOriginCountry;

public class SumOfKeywordinKeywordData {
	
	public  static class SumofKeywordMapperWithSortData extends Mapper 
	<LongWritable,Text,SortKeywordinKeywordData,IntWritable>{
		
		private SortKeywordinKeywordData outputkey = new SortKeywordinKeywordData();
	
		public void map(LongWritable key, Text string, Context context
                ) throws IOException, InterruptedException {
			//JSONObject jsonObject = JSONObject.fromObject(JSONSerializer.toJSON(string.toString()));
			
			System.out.println(string.toString());
			
			String[] value  = string.toString().split(",");

			String value1 =value[1].replaceAll("[\\n\\t]", "");
			outputkey.setKeyword(value[0]);
			outputkey.setSum(Integer.parseInt(value1));
			context.write(outputkey, new IntWritable(outputkey.getSum()));
		}
	}

	public static class SumofKeywordReducerWithSortData 
	extends Reducer<SortKeywordinKeywordData,IntWritable,Text,IntWritable> {
		   private FindOriginCountry finder = new FindOriginCountry();
		   private boolean find = false;
		   private String Keyword = null;
		   
   
			@Override
			protected void setup(Context context)
		              throws IOException,
		                     InterruptedException{
				try {
					finder.loadData();
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				
			    imfDBController controller = new imfDBController();
				controller = new imfDBController("org.gjt.mm.mysql.Driver","jdbc:mysql://localhost:3306/","imf_database");
				controller.setUserName("hive");
				controller.setPassWord("740412");
				try {
					controller.connect();
				} catch (ClassNotFoundException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				
				this.Keyword = controller.GetSearchKeyword();
		
		
             }
		
			@Override
			public void reduce(SortKeywordinKeywordData data, Iterable<IntWritable> values,
	                  Context context
	                  ) throws IOException, InterruptedException {
		
				
				String findword = finder.FindWord(data.getKeyword());
				if(findword != null){
					if(find == false){
						System.out.println("find !!!" + findword);
						String StringValues[] = {this.Keyword, findword};
						if(finder.isKeyword(this.Keyword) == true)
						{
							System.out.println("Update!!");
							finder.UpdateKeywordData(StringValues);
							
						}
						else{
							System.out.println("Insert!!");
							finder.InsertKeywordData(StringValues);
						}

						find = true;
					}
					
					
				}
			   System.out.println(findword);
				
		
				context.write(new Text(data.getKeyword()),new IntWritable(data.getSum()));
				
			}
			

			


	}


}
