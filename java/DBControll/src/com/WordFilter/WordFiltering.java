package com.WordFilter;

import java.io.IOException;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.HashMap;

import com.DBControll.StandardDBController;

public class WordFiltering {

	private HashMap<String, Boolean> m_mapmorpheme;
	private HashMap<String, String> m_exceptionkeyword;
	private Boolean m_bIsexcetionkeyword;
	/* select load file or db*/
	public WordFiltering(String _path){
		m_mapmorpheme = new HashMap<String, Boolean>();
		m_exceptionkeyword = new HashMap<String, String>();
		m_bIsexcetionkeyword = false;
    }
	
	public void loadData() 
			throws IOException, InterruptedException, SQLException
	{
		
		StandardDBController controller = new StandardDBController("org.gjt.mm.mysql.Driver","jdbc:mysql://localhost:3306/","standard_data");
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
		
		ResultSet result = controller.selectTable("morpheme_filteringdata");
		while(result.next()){
			int i=1;
	       String morpheme = result.getString(i++);
	       System.out.println(morpheme);
	       this.addData(morpheme);
		}
		
		ResultSet result2 = controller.selectTable("keyword_filteringdata");
		while(result2.next()){
			int i=1;
	       String morpheme = result2.getString(i++);
	       String keyword = result2.getString(i++);
	       System.out.println(morpheme);
	       System.out.println(keyword);
	       this.addData(morpheme,keyword);
		}

		System.out.println("load!!!!");
	}
	/* add extracted morpheme from komoran to map cf)NNP,SW,SS*/
    public void addData(String morpheme)
			  throws IOException, InterruptedException
	  {
    	if(m_mapmorpheme.get(morpheme) != null){
    			 
    		if(m_mapmorpheme.get(morpheme) == true)
    			return ;
    	}
    	else
    		m_mapmorpheme.put(morpheme, false);
		  
	  }
    
    public void addData(String morpheme,String keyword)
			  throws IOException, InterruptedException
	  {
    	m_mapmorpheme.put(morpheme, true);
    	m_exceptionkeyword.put(morpheme + keyword, keyword);
    	System.out.println("addData!!!");
    	System.out.println(m_mapmorpheme.get(morpheme));
    	System.out.println(m_exceptionkeyword.get(morpheme + keyword));
		  
	  }
    
    /* get keyword wheter information is being or not*/
    public Boolean getMorpheme(String morpheme)
			  throws IOException, InterruptedException
	  {		
    		System.out.println("getMorpheme:" + morpheme);
    		if(null != m_mapmorpheme.get(morpheme))
    		{
    			m_bIsexcetionkeyword = m_mapmorpheme.get(morpheme);
    			System.out.println("m_bIsexcetionkeyword:" + m_bIsexcetionkeyword);
				return true;
    		}
    		else
    		{	
    			//System.out.println("null");
    			m_bIsexcetionkeyword = false;
    			return false;
    		}
		  
	  }
    
    
    public Boolean checkexceptionkeyword(String morpheme,String exceptionkeyword)
    {
    		System.out.println("exceptionkeyword:" + exceptionkeyword);
    		System.out.println("m_exceptionkeyword.get(morpheme + exceptionkeyword) :"+m_exceptionkeyword.get(morpheme + exceptionkeyword));
    		if(null != m_exceptionkeyword.get(morpheme + exceptionkeyword)){
    			System.out.println("m_exceptionkeyword is being!!!");
    			return false;
    		}
    		else
    			return true;
    	
    }
    
    public Boolean confirmfiltering(String morpheme , String exceptionkeyword) 
    		throws IOException, InterruptedException
    {
    	if(this.getMorpheme(morpheme) == false)
    		return false;
       else
       {
    	   if(m_bIsexcetionkeyword == true)
    		   return checkexceptionkeyword(morpheme,exceptionkeyword);
    	   else
    		   return true;
       }
    	
    }
    
}
