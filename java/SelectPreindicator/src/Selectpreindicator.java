
import java.io.IOException;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.Collection;
import java.util.HashMap;
import java.util.List;
import java.util.StringTokenizer;
import java.util.regex.Pattern;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.io.DoubleWritable;
import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.LongWritable;
import org.apache.hadoop.io.NullWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.*;
import org.apache.hadoop.mapreduce.lib.input.FileInputFormat;
import org.apache.hadoop.mapreduce.lib.input.TextInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;
import org.apache.hadoop.mapreduce.lib.output.TextOutputFormat;

import com.ExternalProcess.ProcessRunner;

import java.sql.SQLException;
import java.util.List;

import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.LongWritable;
import org.apache.hadoop.io.NullWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Reducer.Context;

import com.WordFilter.PositiveorNegativeWord;
import com.WordFilter.WordFiltering;
import com.alexholmes.json.mapreduce.MultiLineJsonInputFormat;


import kr.co.shineware.nlp.komoran.core.analyzer.Komoran;
import kr.co.shineware.util.common.model.Pair;

import com.DBControll.DBController;
import com.DBControll.SortDBController;
import com.DBControll.imfDBController;

import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;


public class Selectpreindicator {


	  
	  public static class CommoditydataMapper
	  	extends Mapper<Object, Text, Text, Text>{
		  
		  private Text word = new Text();
		  private Text stringvalue = new Text();
		  

		  
		public JSONObject getJsonObject(String _value){
			JSONObject jsonObject = JSONObject.fromObject(JSONSerializer.toJSON(_value));
			
			return jsonObject;
		}
		
	    protected void setup(Context context) throws IOException, InterruptedException {

	    }


	    public void map(Object key, Text value, Context context
	                    ) throws IOException, InterruptedException {
	    	
	    	String[] StringArrayOfCommoditydata;
		    StringArrayOfCommoditydata = value.toString().split(",");
			word.set(StringArrayOfCommoditydata[0]);
			stringvalue.set(value.toString());
			context.write(word, stringvalue);
	    }
	  }
	 
	  public static class CommoditydataReducer
	  		extends Reducer<Text,Text,Text,Text> {
		  
		   private StringBuffer buffer = new StringBuffer();
			private Text result = new Text();
			private Text commodity = new Text();
			
			public double getSlope(int x1, int x2,double y1, double y2 ){
				double slope = (y2 - y1) / (x2 - x1);
				return slope;
				//return Math.round(slope*100d)/100d;
			}
			
			public double getChangeRate(double y1, double y2 ){
				double rate = y1/y2 * 100;
				return rate;
				//return Math.round(slope*100d)/100d;
			}
			
			public String StringToJsonstring(String _value){
				 String[] StringArrayOfCommoditydata;
				 StringArrayOfCommoditydata = _value.toString().split(",");
				 String commodity = String.format("{\"commodity\" : \"%s\"", StringArrayOfCommoditydata[0]);
				 buffer.append(commodity);
				 String time = String.format(",\"time\" : \"%s\"", StringArrayOfCommoditydata[1]);
				 buffer.append(time);
				 String price = String.format(",\"price\" : %s", StringArrayOfCommoditydata[2]);
				 buffer.append(price);
				 buffer.append("}");
				return buffer.toString();
			}

		    protected void setup(Context context) throws IOException, InterruptedException {

		    }
			
			public void reduce(Text key, Iterable<Text> values,
			                  Context context
			                  ) throws IOException, InterruptedException {

				 
				 System.out.println(key.toString());
				 String jsonstring;
				 int count = 0;
				 long firsttime = 0;
				 long endtime = System.currentTimeMillis();
				 double firstpirce = 0;
				 double endpriece = 0; 
				 long time = 0;
				 JSONObject jsonObject = null;
				 double price = 0;
				 String date ="";
				 Timestamp timestamp = null;
				 for (Text val : values) {
				 	  System.out.println(val.toString()+"\n");
				 	  jsonstring = this.StringToJsonstring(val.toString());
				 	  System.out.println(jsonstring+"\n");
					  jsonObject = JSONObject.fromObject(JSONSerializer.toJSON(jsonstring));
					  price = jsonObject.getDouble("price");
					  date = jsonObject.getString("time");
					  System.out.println("date"+ date + "\n");
					  timestamp = Timestamp.valueOf(date);
					  time = timestamp.getTime();
					  System.out.println("while time!!\n");
					  System.out.print(time);
					  System.out.println("while price!\n");
					  System.out.print(price);
					  if(time > firsttime){
						  firsttime = time;
						  firstpirce = price;  
					  }
					  
					  if(time < endtime){
						  endtime = time;
						  endpriece = price;
					  }
					  count++;
					  buffer.setLength(0);
				 }
				 System.out.println("price!!\n");
				 System.out.print(firstpirce);
				 System.out.println("\n");
				 System.out.print(endpriece);
				 System.out.println("count!!\n");
				 System.out.print(count);
				 System.out.println("\n");
				 
				 double rate = this.getChangeRate(firstpirce,endpriece) - 100;
				 
				 String ratestring = String.format(",%,.2f ", rate);
				 result.set(key.toString() + ratestring);
				 //commodity.set(key.toString());
				 context.write(result,null);
			}
	    
	  }
	  
	  public static String setDatequery(String firstday, String endday){
		 String query = "select category,time,price  from commoditydata where time >='" + firstday + "' and time <='" + endday + "' order by time \\$CONDITIONS";
		 return query;
	  }

	  public static void main(String[] args) throws Exception {
		  
		  
	
       String dir = "/home/jongeun/sqoop-1.4.6.bin__hadoop-2.0.4-alpha";

		String targetdir = args[0]+ "/commodity";
		String columns = "commodity,time,price";
		String table_name = "commoditydata";
		String firstday = "'" + args[2] + "'";
		String endday = "'" + args[3] + "'";
		ProcessRunner procrunner1 = new ProcessRunner();
		procrunner1.byProcessBuilderimportCommoditydata("./preindicator_commoditydata.sh", targetdir,columns, table_name ,firstday,endday, dir);
		
		System.out.println("Completion!! sqoop");

		  
	    Configuration conf = new Configuration();
	    Path out = new Path(args[1]);
	    Path input = new Path(args[0]);
	    Job job = Job.getInstance(conf, "Selectpreindicator");
	    job.setJarByClass(Selectpreindicator.class);
	    job.setMapperClass(CommoditydataMapper.class);
	    job.setReducerClass(CommoditydataReducer.class);
	    job.setInputFormatClass(TextInputFormat.class);
	    job.setOutputFormatClass(TextOutputFormat.class);
	    job.setMapOutputKeyClass(Text.class);
	    job.setMapOutputValueClass(Text.class);
	    job.setOutputValueClass(Text.class);
	    job.setOutputKeyClass(Text.class);
	    FileInputFormat.addInputPath(job, new Path(input,"commodity"));
	    FileOutputFormat.setOutputPath(job, new Path(out,"commodity"));
	    System.out.println("Completion");
	    if(!job.waitForCompletion(true))
	    	return;	
	    
	    
	    String db = "commoditydb";
		DBController controller = new DBController("org.gjt.mm.mysql.Driver","jdbc:mysql://localhost:3306/",db);
		controller.setUserName("hive");
		controller.setPassWord("740412");
		String day = args[3].replace("-", "");
		controller.setTablename(day + "commoditydatachangerate");
		controller.connect();
		
		if(controller.sqlexecuteQuery("select * from " +  controller.getTablename()) == null)
		{
			System.out.println("select * from !@@!!!!!1!!!!!!!!!!!!!");
			String sql = "create table " + controller.getTablename() + "(" +
				    "commodity varchar(50) NOT NULL, \n" +
				    "changerate float(10,3) NULL, \n" +
				    "PRIMARY KEY (commodity) \n" + ")";
			String sqltmp = "create table " + controller.getTablename()+"tmp" + "(" +
				    "commodity varchar(50) NOT NULL, \n" +
				    "changerate float(10,3) NULL, \n" +
				    "PRIMARY KEY (commodity) \n" + ")";
			controller.sqlexecuteUpdate(sql);
			controller.sqlexecuteUpdate(sqltmp);
		}
		else{
			String truncate = "truncate " + controller.getTablename();
			controller.sqlexecuteUpdate(truncate);
			controller.sqlexecuteUpdate(truncate + "tmp");
			System.out.println("truncate!!@@!!!!!1!!!!!!!!!!!!!");
			
		}
	    	
		
		String exporttable_name = controller.getTablename();
		String exporttable_name_tmp = controller.getTablename()+ "tmp";
		String exportoutput = args[1]+ "/commodity";
		System.out.println(exportoutput);
		System.out.println(exporttable_name);
	    String[] command2 = new String[]{"./bin/sqoop", "export","--connect",
	    		"jdbc:mysql://localhost:3306/"+ db,"--username", "hive",
	    		"--password", "740412","--table", exporttable_name, "--staging-table",
	    		exporttable_name_tmp,"--clear-staging-table",  "--export-dir", exportoutput,"-m","1"};

		 ProcessRunner procrunner2 = new ProcessRunner();
		 procrunner2.byProcessBuilder(command2,dir);
		 
		  	
		System.out.println("Completion!!@@!!!!!1");

	  }	

	
	

}
