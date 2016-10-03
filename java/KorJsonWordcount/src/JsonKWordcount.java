

import java.io.IOException;

import java.sql.SQLException;
import java.text.SimpleDateFormat;
import java.util.List;

import java.util.HashMap;




import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.NullWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.*;
import org.apache.hadoop.mapreduce.Mapper.Context;
import org.apache.hadoop.mapreduce.lib.input.FileInputFormat;
import org.apache.hadoop.mapreduce.lib.input.TextInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;
import org.apache.hadoop.mapreduce.lib.output.TextOutputFormat;


import com.alexholmes.json.mapreduce.MultiLineJsonInputFormat;


import kr.co.shineware.nlp.komoran.core.analyzer.Komoran;
import kr.co.shineware.util.common.model.Pair;


import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

import java.util.Date;


import com.DBControll.SortDBController;

import com.ExternalProcess.ProcessRunner;
import com.WordFilter.PositiveorNegativeWord;
import com.WordFilter.WordFiltering;




public class JsonKWordcount {
	

  
  
  
  
  public static String getTodayData(){

      Date d = new Date();
       
      SimpleDateFormat sdf = new SimpleDateFormat("yyyyMMdd");
     
      return sdf.format(d);
	  	
  }
  

  
  public static Boolean ExceptString(String _string)
  throws IOException, InterruptedException
  {
	  if(_string.equals("…") || _string.equals("[") ||
			  _string.equals("]") || _string.equals(")") ||
			  _string.equals("(") || _string.equals(")"))
		  return true;
	  else 
		  return false;
	
	  
  }
  
  

  
  

  
  public static class JsonTokenizerMapper
  	extends Mapper<Object, Text, Text, IntWritable>{

	  public WordFiltering wordFilter= new WordFiltering("");
	  
	  public Komoran komoran = new Komoran("/home/jongeun/hadoop-2.7.2/customjar/dictionary/");
		
	  public String extractjsonname = "title";
	  
	  private HashMap<String, Boolean> wordmap = new HashMap<String, Boolean>();
	  
	  private Text word = new Text();
    
      public  IntWritable currentIndex = new IntWritable(0);
	  
	  public  String ConvertJsonToNormalstring(String jsonstring)
	  throws IOException, InterruptedException
	  {
		  JSONObject jsonObject = JSONObject.fromObject(JSONSerializer.toJSON(jsonstring));

		  String StringIndex = jsonObject.getString("index");
		  currentIndex = new IntWritable(Integer.parseInt(StringIndex));
		  
		  String str = jsonObject.getString(extractjsonname);
		  //System.out.println("title:"+str);
		  String refinestr1 = str.toString().replaceAll("[\\n\\t]", "");
		  String refinestr2 = refinestr1.toString().replaceAll("[\"\"\\'\'\\,\\.]", " ");
		  String refinestr3  = refinestr2.toString().replace("[", " ");
		  String refinestr4  = refinestr3.toString().replace("]", " ");
		  return refinestr4;
		  
	  }

    protected void setup(Context context) throws IOException, InterruptedException {
        try {
			wordFilter.loadData();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
    }


    public void map(Object key, Text value, Context context
                    ) throws IOException, InterruptedException {
    	
    	String formatted = ConvertJsonToNormalstring(value.toString());
    	//한구문에서 중복되는 어절을 체크하기 위해서 쓰이는 맵 함수
    	wordmap.clear();
    	
    	System.out.print(formatted);
    	System.out.print(currentIndex);
    	
    	List<List<Pair<String,String>>> result = komoran.analyze(formatted);
		for (List<Pair<String, String>> eojeolResult : result) {
			for (Pair<String, String> wordMorph : eojeolResult) 
			{
				if(null == wordmap.get(wordMorph.getFirst()))
				{
					wordmap.put(wordMorph.getFirst(), true);
					
					if(wordFilter.confirmfiltering(wordMorph.getSecond(), wordMorph.getFirst()) == true)
					{
;
							word.set(wordMorph.getSecond() + ":" +  wordMorph.getFirst());
							//word.set(wordMorph.getFirst());
							context.write(word, currentIndex);
	
					}
				}
			}
		}
    }
  }
 
  public static class IntSumReducer
  		extends Reducer<Text,IntWritable,Text,Text> {
	  
		private final static IntWritable one = new IntWritable(1);
		private Text result = new Text();
		private StringBuffer buffer = new StringBuffer();
		
		private PositiveorNegativeWord wordDirection= new PositiveorNegativeWord();

	    protected void setup(Context context) throws IOException, InterruptedException {
	        try {
	        	wordDirection.loadData();
			} catch (SQLException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
	    }
		
		public void reduce(Text key, Iterable<IntWritable> values,
		                  Context context
		                  ) throws IOException, InterruptedException {
		 
		
		 result.clear();
		 buffer.setLength(0);
		 int sum = 0;
		 
		 String[] StringArrayOfkeywordandmorpheme;
		 StringArrayOfkeywordandmorpheme = key.toString().split(":");
		 String morpheme = String.format("{\"morpheme\" : \"%s\"", StringArrayOfkeywordandmorpheme[0]);
		 buffer.append(morpheme);
		 String keyword = String.format(",\"keyword\" : \"%s\"", StringArrayOfkeywordandmorpheme[1]);
		 buffer.append(keyword);
		 String direction = String.format(",\"direction\" : \"%d\"", wordDirection.findWord(StringArrayOfkeywordandmorpheme[1]));
		 buffer.append(direction);
		 buffer.append(", \"keywordindex\" : [");
		 
		 for (IntWritable val : values) {
		  // sum += val.get();
			  sum = sum + one.get();
			  buffer.append(val.get());
			  buffer.append(',');
		 }
		 buffer.replace(buffer.length()- 1, buffer.length(),"");
		 buffer.append("]");
		 String sumstring = String.format(", \"sum\" : %d }", sum);
		 buffer.append(sumstring);
		 result.set(buffer.toString());
	
		 context.write(result,null);
		 
	}
    
  }

  public static void main(String[] args) throws Exception {
	System.out.println("cleanup");
	SortDBController controller = new SortDBController("org.gjt.mm.mysql.Driver","jdbc:mysql://localhost:3306/","sorting_data");
	controller.setUserName("hive");
	controller.setPassWord("740412");
	controller.setDay(args[2]);
	controller.setTablename("sorting_table");
	controller.connect();
	
	if(controller.CheckBeTable(controller.getTablename()) == null)
	{
		controller.CreateSorttable(controller.getTablename());
		controller.CreateSorttable(controller.getTablename()+ "tmp");
	}
	else{
		controller.DeleteSorttable(controller.getTablename());
	}
	 
    Configuration conf = new Configuration();
    Path out = new Path(args[1]);
    Job job = Job.getInstance(conf, "JsonKWordcount");
    job.setJarByClass(JsonKWordcount.class);
    job.setMapperClass(JsonTokenizerMapper.class);
    job.setReducerClass(IntSumReducer.class);
    job.setInputFormatClass(MultiLineJsonInputFormat.class);
    MultiLineJsonInputFormat.setInputJsonMember(job, args[3]);
    job.setOutputFormatClass(TextOutputFormat.class);
    job.setMapOutputKeyClass(Text.class);
    job.setMapOutputValueClass(IntWritable.class);
    job.setOutputValueClass(Text.class);
    job.setOutputKeyClass(Text.class);
    FileInputFormat.addInputPath(job, new Path(args[0]));
    FileOutputFormat.setOutputPath(job, new Path(out,"raw"));
    System.out.println("Completion");
    if(!job.waitForCompletion(true))
    	return;


    Configuration conf2 = new Configuration();
    Job job2 = Job.getInstance(conf2, "SumofJsonSortJsonData");
    job2.setJarByClass(SumofJsonSortJsonData.class);
    //job2.setJarByClass(DBController.class);
    job2.setMapperClass(SumofJsonSortJsonData.SumofJsonMapperWithSortJsonData.class);
    job2.setReducerClass(SumofJsonSortJsonData.SumofJsonReducerWithSortJsonData.class);
    job2.setInputFormatClass(TextInputFormat.class);
    job2.setSortComparatorClass(SortJsonDataComparator.class);
    job2.setMapOutputKeyClass(SortJsonData.class);
    job2.setMapOutputValueClass(IntWritable.class);
    job2.setOutputValueClass(Text.class);
    job2.setOutputKeyClass(NullWritable.class);
    //job2.setOutputFormatClass(TextOutputFormat.class);
    FileInputFormat.addInputPath(job2, new Path(out,"raw"));
    FileOutputFormat.setOutputPath(job2, new Path(out,"sort"));
    //System.out.println("Completion");
    if(!job2.waitForCompletion(true))
    	return; 
    
    String dir = "/home/jongeun/sqoop-1.4.6.bin__hadoop-2.0.4-alpha";
    
	String table_name = controller.getDay() +  controller.getTablename();
	String table_name_tmp = controller.getDay() +  controller.getTablename()+"tmp";

    String[] command2 = new String[]{"./bin/sqoop", "export","--connect",
    		"jdbc:mysql://localhost:3306/sorting_data","--username", "hive",
    		"--password", "740412","--table", table_name, "--staging-table",
    		table_name_tmp, "--clear-staging-table", "--input-fields-terminated-by", "\n", "--export-dir", args[1]+"/sort","-m","1"};

	 ProcessRunner procrunner1 = new ProcessRunner();
	 procrunner1.byProcessBuilder(command2,dir);
	 
	  	
	System.out.println("Completion!!@@!!!!!1");

	

   
  }	

}
