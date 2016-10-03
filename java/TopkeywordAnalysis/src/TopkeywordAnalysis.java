
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.net.URLDecoder;
import java.net.URLEncoder;
import java.sql.SQLException;
import java.util.HashMap;
import java.util.List;
import java.util.StringTokenizer;
import java.util.regex.Pattern;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
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
import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;
import net.sf.json.JSONArray;

import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.LongWritable;
import org.apache.hadoop.io.NullWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Reducer.Context;

import com.WordFilter.WordFiltering;

import kr.co.shineware.nlp.komoran.core.analyzer.Komoran;
import kr.co.shineware.util.common.model.Pair;


import com.DBControll.imfDBController;

public class TopkeywordAnalysis {
	
	
	
	
	   public static boolean isStringDouble(String s) {
		    try {
		        Double.parseDouble(s);
		        return true;
		    } catch (NumberFormatException e) {
		        return false;
		    }
	   }

		public static String  createMultipleCallIndexSqlofWhere(JSONArray array){
			 StringBuilder sql = new StringBuilder();
			  for (int i = 0; i < array.size(); i++){
				  String cindexstring  = null;
				   if(i < array.size() - 1 )
				      cindexstring = String.format(" cindex = %d or", array.get(i));
				   else
					   cindexstring = String.format(" cindex = %d", array.get(i));	 
				   
				   sql.append(cindexstring);
			  }
			  
			  return sql.toString();
		
		}
		
		
		public static HashMap<Integer, Boolean>   createKeywordHashmap(JSONArray array){
	
			 HashMap<Integer, Boolean> _keywordhashmap = new HashMap<Integer, Boolean>();
			  for (int i = 0; i < array.size(); i++){
				  _keywordhashmap.put((Integer) array.get(i), true);
			  }
			  
			  return _keywordhashmap;
		
		}
		

		
		public  static class CrawlingdataKeywordMapper extends Mapper 
		<LongWritable,Text,Text,IntWritable>{
			
			private CollectTopKeyword m_Collector = null;
			
			private HashMap<Integer, Boolean> m_SerachKeywordHashMap = null;
			
			public WordFiltering wordFilter= new WordFiltering("");
			
			public Komoran komoran = new Komoran("/home/jongeun/hadoop-2.7.2/customjar/dictionary/");
			
		   private Text word = new Text();
			 
		   private IntWritable increaement = new IntWritable(1);
		   
		   boolean check = false;
			
			@Override
		   protected void setup(Context context) throws IOException, InterruptedException {
				m_Collector = new CollectTopKeyword();
				  try {
					  m_Collector.connectDB("org.gjt.mm.mysql.Driver", "jdbc:mysql://localhost:3306/","sorting_data", "hive", "740412");
				} catch (ClassNotFoundException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			   String jsonString = null;
				try {
					jsonString = m_Collector.getSelectSortTable("sorting_table", "20150812", "위안화");
				} catch (ClassNotFoundException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			
			  JSONObject jsonObject = JSONObject.fromObject(JSONSerializer.toJSON(jsonString));
			  
			  JSONArray keywordindexarray = jsonObject.getJSONArray("keywordindex");
			  String keyword = jsonObject.getString("keyword");
			  String morpheme =  jsonObject.getString("morpheme");

			  m_SerachKeywordHashMap = createKeywordHashmap(keywordindexarray);
			  
			  
		        try {
					wordFilter.loadData();
					wordFilter.addData(morpheme, keyword);
					
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			  
		    }

		   @Override
			public void map(LongWritable key, Text value, Context context
	                ) throws IOException, InterruptedException {
			   	  int numInt = 0;
    		   
    			  String refinestr1 = value.toString().replaceAll("[\\n\\t]", "");
    			  String refinestr2 = refinestr1.toString().replaceAll("[\"\"\\'\'\\,\\.]", " ");
    			  String refinestr3  = refinestr2.toString().replace("[", " ");
    			  String refinestr4  = refinestr3.toString().replace("]", " ");
    			  System.out.println("!!value :" +refinestr4 + "\n");
    			  
    			  //String str =refinestr4.replaceAll("\\p{Z}", "");
    			  
    			  if(isStringDouble(refinestr4)){
				      String num = refinestr4.trim();
				      System.out.println("!!number :" +num + "\n");
    				  numInt = Integer.parseInt(num);
			    	   check = false;
			    	   if(m_SerachKeywordHashMap.get(numInt) != null)
			    		   check = true;
    			  }else{
    				  if(check == true){
    					  System.out.println("check: " + refinestr4);
		    			 	List<List<Pair<String,String>>> result = komoran.analyze(refinestr4);
		    				for (List<Pair<String, String>> eojeolResult : result) {
		    					for (Pair<String, String> wordMorph : eojeolResult) 
		    					{
		    					
		    							if(wordFilter.confirmfiltering(wordMorph.getSecond(), wordMorph.getFirst()) == true)
		    							{
		    		;							IntWritable currentIndex = new IntWritable(1);
		    									word.set(wordMorph.getFirst());
		    									context.write(word, increaement);
		    			
		    							}
		    						
		    					}
		    				}
		    				check = false;
			    	   }  
    			  }
		    }
		}
    			  
    			 
			
		
		

		public static class CrawlingdataKeywordReducer 		
			extends Reducer<Text,IntWritable,Text,Text> {
				public int sum = 0;
				private final static IntWritable one = new IntWritable(1);
				
				@Override
				public void reduce(Text keyword, Iterable<IntWritable> values,
		                  Context context
		                  ) throws IOException, InterruptedException {

					 sum = 0;
					 for (IntWritable val : values) {
						  // sum += val.get();
							  sum = sum + one.get();
							  
					 }
					// IntWritable iInteger = new IntWritable(sum); 
					 String output = keyword + String.format(",%d", sum);
					 Text key = new Text(output);
					 Text value = new Text("");
					 context.write(key,value);
					
				}
				@Override
				protected void cleanup(Context context)
		                throws IOException, InterruptedException{
					
				

				}

		}
		
	  private static String encodeTodecode(String _convertString, String[] charset) throws UnsupportedEncodingException{
			   

				 String encoded = URLEncoder.encode(_convertString, charset[0]);
			     String decoded = URLDecoder.decode(encoded, charset[1]);
				 return decoded;
				 
		 }
			  
	  public static  String searchKeyword = null;
	  
	  public static void main(String[] args) throws Exception {
		  //args[0]=out, args[1]=export targetdir or inputdata dir, args[2] = table_name args[3]=columns ,args[4]=keyword,
		  Path out = new Path(args[0],"output"); // /user/www-data/topkeyword
		  
		  String targetdir = args[0]+ "/import/" + args[1]; //"20150812"
		  
		  String table_name = args[1] + "crawling_rawdata";
		  
		  String columns  = args[2]; // ex)cindex,title
		  
		  searchKeyword = args[3]; // keyword 

			
		  CollectTopKeyword Collector = new CollectTopKeyword();
		  Collector.connectDB("org.gjt.mm.mysql.Driver", "jdbc:mysql://localhost:3306/","sorting_data", "hive", "740412");
		  String jsonString = Collector.getSelectSortTable("sorting_table", args[1], searchKeyword);

		  System.out.println("jsonstring!!!!!"); 
		  System.out.println(jsonString); 
		  
		  JSONObject jsonObject = JSONObject.fromObject(JSONSerializer.toJSON(jsonString));
		  
		  JSONArray keywordindexarray = jsonObject.getJSONArray("keywordindex");
		  String StringofkeyworArraySize = String.format("size : %d", keywordindexarray.size());
		  System.out.print(StringofkeyworArraySize); 
  
		 String dir = "/home/jongeun/sqoop-1.4.6.bin__hadoop-2.0.4-alpha";
		
		 
		 String sqlWhere = TopkeywordAnalysis.createMultipleCallIndexSqlofWhere(keywordindexarray);
		 
		  ProcessRunner procrunner2 = new ProcessRunner();
		  procrunner2.byProcessBuilderRedirectCrawlingraw("./crawling_dataimport.sh", targetdir,columns, table_name ,sqlWhere, dir);
		  System.out.println("Completion!!@@!!!!!2");
		  
		  
	    Configuration conf = new Configuration();
	    Job job = Job.getInstance(conf, "TopkeywordAnalysis");
	    job.setJarByClass(TopkeywordAnalysis.class);
	    job.setMapperClass(CrawlingdataKeywordMapper.class);
	    job.setReducerClass(CrawlingdataKeywordReducer.class);
	    job.setInputFormatClass(TextInputFormat.class);
	    job.setOutputFormatClass(TextOutputFormat.class);
	    job.setMapOutputKeyClass(Text.class);
	    job.setMapOutputValueClass(IntWritable.class);
	    job.setOutputKeyClass(Text.class);
	    job.setOutputValueClass(IntWritable.class);
	    FileInputFormat.addInputPath(job, new Path(targetdir));
	    FileOutputFormat.setOutputPath(job, new Path(out,"TopkeywordAnalysis"));
	    System.out.println("Completion");
	    if(!job.waitForCompletion(true))
	    	return;
	    imfDBController controller = new imfDBController();
		controller = new imfDBController("org.gjt.mm.mysql.Driver","jdbc:mysql://localhost:3306/","imf_database");
		controller.setUserName("hive");
		controller.setPassWord("740412");
		controller.connect();
		
		if(controller.isSearchKeywordTable() == true){
			controller.UpdateSearchData(searchKeyword);			
		}
		else{
			controller.InsertSearchData(searchKeyword);
		}
	    
	    Configuration conf2 = new Configuration();
	    Job job2 = Job.getInstance(conf2, "SumOfKeywordinKeywordData");
	    job2.setJarByClass(SumOfKeywordinKeywordData.class);
	    job2.setMapperClass(SumOfKeywordinKeywordData.SumofKeywordMapperWithSortData.class);
	    job2.setReducerClass(SumOfKeywordinKeywordData.SumofKeywordReducerWithSortData.class);
	    job2.setInputFormatClass(TextInputFormat.class);
	    job2.setSortComparatorClass(SortKeywordinKeywordDataComparator.class);
	    job2.setMapOutputKeyClass(SortKeywordinKeywordData.class);
	    job2.setMapOutputValueClass(IntWritable.class);
	    job2.setOutputValueClass(IntWritable.class);
	    job2.setOutputKeyClass(Text.class);
	    FileInputFormat.addInputPath(job2, new Path(out,"TopkeywordAnalysis"));
	    FileOutputFormat.setOutputPath(job2, new Path(out,"TopkeywordAnalysis/sort"));
	    if(!job2.waitForCompletion(true))
	    	return;
	
	  }
	  
	

}
